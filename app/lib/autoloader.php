<?php
  /**
   * File:         autoloader.php
   * Description:  parsing, cacheing and namespace-aware autoloader
   * Version:      1.0
   * Author:       Richard Keizer
   * Email:        ra dot keizer at gmail dot com
   * ------------------------------------------------------------------------------
   * COPYRIGHT (c) 2011 Richard Keizer
   *
   * The source code included in this package is free software; you can
   * redistribute it and/or modify it under the terms of the GNU General Public
   * License as published by the Free Software Foundation. This license can be
   * read at:
   *
   * http://www.opensource.org/licenses/gpl-license.php
   *
   * This program is distributed in the hope that it will be useful, but WITHOUT 
   * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
   * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. 
   * ------------------------------------------------------------------------------
   *
   *
   * Usage (see bottom of this file):
   *
   *  Autoloader::create($cachefilename, $excludelist);
   *  e.g:
   *  Autoloader::create('classes.cache', array('3rdparty', '\.svn', '^images$'));
   *
   *
   *  Most autoloaders do nothing more than loading files with names similar to the
   *  classnames. This one does more:
   *  As soon as a class can't be found this autoloader will parse all .php files
   *  in all folders recursively. It extracts a cache containing class/filename pairs.
   *  This is done with respect to the namespace the class is in.
   *  These pairs are then used to include the correct files.
   *
   *
   *
   */  
  
  class ClassCache {
    
    protected $items = array();
    
    public function __construct($filename) {
      $this->filename = $filename;
      $this->loadFromFile($filename);
    }
    
    public function reset() {
      $this->items = array();
    }
    
    public function isEmpty() {
      return empty($this->items);
    }
    
    public function addClassFilename($classname, $filename) {
      $classname = strtolower($classname);
      $this->items[$classname] = $filename;               //detect collisions here!
    }
    
    public function getClassFilename($classname) {
      $classname = strtolower($classname);
      return isset($this->items[$classname]) ? $this->items[$classname] : null;
    }
    
    public function loadFromFile() {
      $this->items = unserialize(@file_get_contents($this->filename));
    }
    
    public function saveToFile() {
      file_put_contents($this->filename, serialize($this->items));
    }
  }
  
  
  
  
  class Autoloader {
    protected $cache;
    protected $excludelist = array();
    protected static $instance;
    
    protected function __construct($cachefilename, $excludelist) {
      $this->excludelist = $excludelist;
      $this->cache = new ClassCache($cachefilename);
      if ($this->cache->isEmpty()) $this->rebuildCache();
      $this->register();
    }
    
    public static function create($cachefilename, array $excludelist = array()) {
      if (!isset(self::$instance)) {
        $className = __CLASS__;
        self::$instance = new $className($cachefilename, $excludelist);
      }
      return self::$instance;
    }
        
    protected function register() {
      spl_autoload_register(array($this, 'autoloadHandler'));
    }
        
    protected function autoloadHandler($classname) {
      if (!$this->includeClass($classname)) {  //on fail...
        $this->rebuildCache();                        //rebuild cache...
        $this->includeClass($classname);       //and try again...
      }
    }
        
    protected function includeClass($classname) {
      if ($filename = $this->cache->getClassFilename($classname)) {
        include $filename;
        return true;
      }
      return false;
    }
    
    protected function shouldFollow($fsnode) {
      if (!is_dir($fsnode) || in_array(basename($fsnode), array('.', '..'))) return false;
      foreach($this->excludelist as $item) if (preg_match("/{$item}/i", basename($fsnode))) return false;
      return true;
    }
        
    protected function rebuildCache() {
      $this->cache->reset();
      
      $stack = array('./');        //push current folder
      while (!empty($stack)) {
        
        $folder = array_shift($stack);
        
        if ($h = opendir($folder)) {
          while(($child = readdir($h)) !== FALSE) {
            
            if ($this->shouldFollow($folder.$child)) {
              array_push($stack, $folder.$child.'/');
            } elseif (is_file($folder.$child) && preg_match("/\.class.php$/i", $folder.$child)) {
              $tokens = token_get_all(php_strip_whitespace($folder.$child));
              $namespace = '';         //default namespace
              $status = null;
              for($i = 0; $i < count($tokens); $i++) {
                switch ($tokens[$i][0]) {
                  case T_NAMESPACE: {
                    $namespace = '';
                    $status = T_NAMESPACE;
                    break;
                  }
                  case T_CLASS: {
                    $classname = '';
                    $status = T_CLASS;
                    break;
                  }
                  default: {
                    switch ($status) {
                      case T_NAMESPACE: {
                        if (isset($tokens[$i][0]) && $tokens[$i][0] == T_STRING) {
                          $namespace .= $tokens[$i][1].'\\';
                        } elseif (!is_array($tokens[$i]) && in_array($tokens[$i], array(';', '{'))) {
                          $status = null;
                        }
                        break;
                      }
                        
                      case T_CLASS: {
                        if (is_array($tokens[$i]) && $tokens[$i][0] == T_STRING) {
                          $this->cache->addClassFilename($namespace.$tokens[$i][1], $folder.$child);
                          $status = null;
                        }
                        break;                    
                      }
                    } //switch status
                  } //case token default
                } //switch token
              } //end for
            }
          }
        }
      }
      $this->cache->saveToFile();
    }
  }
  
  
  
   