<?php

/**
  * Rotina Active Record
  *@author Danyllo C.
  */
  
  class Rotina extends TRecord
  {
  
      const TABLENAME = 'rotina';
      const PRIMARYKEY = 'idRotina';
      const IDPOLICY = 'max';
      
      public function __construct($id = NULL, $callObjectLoad = TRUE)
      {
      
          parent::__construct($id, $callObjectLoad);
          parent::addAttribute('nome');
          parent::addAttribute('dtIni');
          parent::addAttribute('dtFim');
          parent::addAttribute('freq');
          
      }
          
  }