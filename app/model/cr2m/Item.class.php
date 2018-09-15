<?php

/**
  * Máquina Active Record
  *@author Danyllo C.
  */
  
  class Item extends TRecord
  {
  
      const TABLENAME = 'item';
      const PRIMARYKEY = 'idItem';
      const IDPOLICY = 'max';
      
      public function __construct($id = NULL, $callObjectLoad = TRUE)
      {
      
          parent::__construct($id, $callObjectLoad);
          parent::addAttribute('nome');
          parent::addAttribute('qtd');
          
      }
          
  }