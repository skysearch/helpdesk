<?php
/**
 * Validador para Cadastro de Pessoa Jurídica
 *
 * @author   Wanderson Henrique Camargo Rosa
 * @category Hazel
 * @package  Hazel_Validator
 */
class Sky_Form_Validate_Cnpj extends Sky_Form_Validate_Document_Abstract
{
    /**
     * Tamanho do Campo
     * @var int
     */
    protected $_size = 14;
 
    /**
     * Modificadores de Dígitos
     * @var array
     */
    protected $_modifiers = array(
        array(5,4,3,2,9,8,7,6,5,4,3,2),
        array(6,5,4,3,2,9,8,7,6,5,4,3,2)
    );
}