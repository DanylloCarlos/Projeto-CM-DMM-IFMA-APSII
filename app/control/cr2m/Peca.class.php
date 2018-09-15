<?php

/**
  * Peça Active Record
  *@author Danyllo C.
  */
  
  class Maquina extends TRecord
  {
  
      const TABLENAME = 'peca';
      const PRIMARYKEY = 'idPeca';
      const IDPOLICY = 'max';
      
      public function __construct($id = NULL, $callObjectLoad = TRUE)
      {
      
          parent::__construct($id, $callObjectLoad);
          parent::addAttribute('codigo');
          parent::addAttribute('nome');
          parent::addAttribute('modelo');
          parent::addAttribute('validade');
          parent::addAttribute('garantia');
          parent::addAttribute('descricao');
          
      }
          
  }