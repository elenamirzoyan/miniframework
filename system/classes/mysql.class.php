<?php

// mysql.class.php



class Mysql
{
  private $class_name          = "MySql";
  private $class_version       = "1.1.1";
  private $class_author        = "Elena Mirzoyan";
  private $class_source        = "";

  private $db_link             = 0;

  private $hostname            = "";
  private $username            = "";
  private $password            = "";
  private $database            = "";
  private $persistant          = 0;

  var     $affected_rows       = 0;
  var     $num_rows            = 0;

  private $auto_strip_slashes  = 0;

  public function class_name()
    {
      return $this->class_name;
    }

  public function class_version()
    {
      return $this->class_version;
    }

  public function class_author()
    {
      return $this->class_author;
    }

  public function class_source()
    {
      return $this->class_source;
    }

  public function __construct($hostname, $username, $password, $database, $persistant = 0)
    {
      $this->hostname   = $hostname;
      $this->username   = $username;
      $this->password   = $password;
      $this->database   = $database;
      $this->persistant = $persistant;
    }

  public function connect()
    {
      if ($this->persistant)
        $this->db_link = mysql_pconnect($this->hostname, $this->username, $this->password);
      else
        $this->db_link = mysql_connect($this->hostname, $this->username, $this->password);

      if (!$this->db_link)
        {
          $this->error("Connection Credentials Failed");
        }

      if (!mysql_select_db($this->database, $this->db_link))
        {
          $this->error("Database Select Failed");
        }
      $this->hostname = '';
      $this->username = ''; 
      $this->password = '';
      $this->database = '';
		@mysql_query('SET NAMES UTF8');
      return $this->db_link;
    }

  public function close()
    {
      if (!$this->db_link)
        {
          $this->error("Not Connected");
        }
      if (!mysql_close($this->db_link))
        {
          $this->error("Connection Close Failed");
        }
      $this->db_link = 0;
    }

  private function my_stripslashes($str)
    {
      $str = is_array($str) ? array_map('my_stripslashes', $str) : stripslashes($str);

      return $str;
    }

  private function my_addslashes($str)
    {
      $str = is_array($str) ? array_map('my_addslashes', $str) : addslashes($str);

      return $str;
    }

  private function my_mysql_real_escape_string($str)
    {
      $str = is_array($str) ? array_map('my_mysql_real_escape_string', $str) : mysql_real_escape_string($str);

      return $str;
    }

  public function escape($str)
    {
      if (get_magic_quotes_gpc())
        {
          $str = my_stripslashes($str);
        }
      if (function_exists("mysql_real_escape_string"))
        {
          $str = my_mysql_real_escape_string($str);
        }
      else
        {
          $str = my_addslashes($str);
        }
      return $str;
    }

  public function query($query)
    {
      if (!$this->db_link)
        {
          $this->error("No Database Connection Found!");
        }
      if (!$results = mysql_query($query))
        {
          $this->error("Query Failed - '$query'");
        }
      if ((stristr($query, 'select') === FALSE) || (stristr($query, 'from') === FALSE))
        $this->affected_rows = mysql_affected_rows($this->db_link);
      else
        $this->num_rows = mysql_num_rows($results);
      return $results;
    }

  public function fetch_array($results)
    {
      if (!$this->db_link)
        {
          $this->error("Not Connected");
        }
      if (!$results)
        {
          $this->error("fetch_array Failed");
        }
      if (!$row = mysql_fetch_array($results))
        return false;
      if ($this->auto_strip_slashes)
        {
          foreach ($row as $key => $value)
            {
            
              $row[$key] = stripslashes($value);
            }
        }
      return $row;
    }

  public function fetch_all_array($query)
    {
      if (!$this->db_link)
        {
          $this->error("Not Connected");
        }
      if (!$result = $this->query($query))
        {
          return false;
        }
      $out = array();

      while ($row = $this->fetch_array($result))
        {
          if ($this->auto_strip_slashes)
            {
              foreach ($row as $key => $value)
                {
                  $row[$key] = stripslashes($value);
                }
            }
          $out[] = $row;
        }

      mysql_free_result($result);
      return $out;
    }

  private function is_meta_injection($str)
    {
      if (preg_match("/(\%27)|(\')|(\-\-)|(\%23)|(\#)/ix", $str, $matches) == true)
        {
          return true;
        }
      if (preg_match("/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i", $str, $matches) == true)
        {
          return true;
        }
      if (preg_match("/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/ix", $str, $matches) == true)
        {
          return true;
        }
      if (preg_match("/((\%27)|(\'))union/ix", $str, $matches) == true)
        {
          return true;
        }
      return false;
    }

  private function is_css_injection($str)
    {
      if (preg_match("/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/ix", $str, $matches) == true)
        {
          return true;
        }
      if (preg_match("/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i", $str, $matches) == true)
        {
          return true;
        }
      if (preg_match("/((\%3C)|<)[^\n]+((\%3E)|>)/i", $str, $matches) == true)
        {
          return true;
        }
      return false;
    }

  public function is_injection($str)
    {
      if ($this->is_meta_injection($str))
        return true;

      if ($this->is_css_injection($str))
        return true;
      return false;
    }

  public function strip_html($str)
    {
      $search = array('@<script[^>]*?>.*?</script>@si', '@<[\/\!]*?[^<>]*?>@si', '@<style[^>]*?>.*?</style>@siU', '@<![\s\S]*?--[ \t\n\r]*>@');

      $str = preg_replace($search, '', $str);

      return $str;
    }

  public function error($str)
    {
      if (!$str)
        return;

      echo "<BR><BR><font color=\"#ff0000\"><B>Database Error: $str</b></font><BR><BR>\n";
      exit;
    }
}

?>
