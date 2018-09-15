<?php

/**
  * Máquina Active Record
  *@author Danyllo C.
  */
  
  class Maquina extends TRecord
  {
  
      const TABLENAME = 'maquina';
      const PRIMARYKEY = 'idMaquina';
      const IDPOLICY = 'max';
      
      public function __construct($id = NULL, $callObjectLoad = TRUE)
      {
      
          parent::__construct($id, $callObjectLoad);
          parent::addAttribute('nome');
          parent::addAttribute('marca');
          parent::addAttribute('modelo');
          parent::addAttribute('dimensao');
          parent::addAttribute('potencia');
          
      }
          
  }