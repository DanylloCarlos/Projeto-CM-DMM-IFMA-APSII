<?php

/**
  * Responsável Active Record
  *@author Danyllo C.
  */
  
  class Responsavel extends TRecord
  {
  
      const TABLENAME = 'responsavel';
      const PRIMARYKEY = 'idResp';
      const IDPOLICY = 'max';
      
      public function __construct($id = NULL, $callObjectLoad = TRUE)
      {
      
          parent::__construct($id, $callObjectLoad);
          parent::addAttribute('cpf');
          parent::addAttribute('formacao');
          parent::addAttribute('funcao');
          parent::addAttribute('empresa');
          
      }
          
  }