$.mask.options = {
  attr: 'alt',              // an attr to look for the mask name or the mask itself
  mask: null,               // the mask to be used on the input
  type: 'fixed',            // the mask of this mask
  maxLength: -1,            // the maxLength of the mask
  defaultValue: '',         // the default value for this input
  textAlign: true,          // to use or not to use textAlign on the input
  selectCharsOnFocus: true, //selects characters on focus of the input
  setSize: false,           // sets the input size based on the length of the mask (work with fixed and reverse masks only)
  autoTab: true,            // auto focus the next form element
  fixedChars: '[(),.:/ -]', // fixed chars to be used on the masks.
  onInvalid: function(){},
  onValid: function(){},
  onOverflow: function(){}
};

$.mask.masks = {
  'phone'         : { mask : '(99) 9999-9999' },
  'tel'         : { mask : '(99) 99999-9999' },
  'phone-us'          : { mask : '(999) 9999-9999' },
  'cpf'           : { mask : '999.999.999-99' },
  'cnpj'          : { mask : '99.999.999/9999-99' },
  'date'          : { mask : '39/19/9999' }, //uk date
  'date-us'       : { mask : '19/39/9999' },
  'cep'           : { mask : '99999-999' },
  'time'          : { mask : '29:69' },
  'cc'            : { mask : '9999 9999 9999 9999' }, //credit card mask
  //'number'       : { mask : '999999.999.999', type : 'reverse' },
  'integer'       : { mask : '99.9999', type : 'reverse' },
  'decimal'       : { mask : '99,999.999.999.999', type : 'reverse', defaultValue: '000' },
  //'currency'       : { mask : '99,999.999.999.999', type : 'reverse', defaultValue: '000' },
  'decimal-us'        : { mask : '99.999,999,999,999', type : 'reverse', defaultValue: '000' },
  'signed-decimal'    : { mask : '99,999.999.999.999', type : 'reverse', defaultValue : '+000' },
  'signed-decimal-us' : { mask : '99,999.999.999.999', type : 'reverse', defaultValue : '+000' }
}

jQuery(function($) {
    $('input[type="text"][alt!="currency"]').setMask();
});