<?php
namespace Administrator\InputFilter;

use Blx\InputFilter\AbstractInputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\ValidatorChain;
use Zend\Validator\Digits;
use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\EmailAddress;
use Zend\Validator\Db\RecordExists;
use Zend\InputFilter\InputFilter;

class CreateUser extends AbstractInputFilter
{

    public function __construct($serviceManager)
    {
        parent::__construct($serviceManager);
        
        // Input
        $userIdInput = new Input('ID');
        
        $userGroupInput = new Input('group');
        $userGroupValidator = new ValidatorChain();
        $userGroupValidator->addValidator(new RecordExists(array(
            'table' => 'user-groups',
            'field' => 'group_id',
            'adapter' => $this->serviceManager->get('db')
        )));
        $userGroupInput->setValidatorChain($userGroupValidator);
        
        $fullNameInput = new Input('fullName');
        $fullNameValidator = new ValidatorChain();
        $fullNameValidator->addValidator(new NotEmpty());
        $fullNameValidator->addValidator(new Alnum(array(
            'allowWhiteSpace' => true
        )));
        $fullNameValidator->addValidator(new StringLength(array(
            'min' => 2,
            'max' => 32
        )));
        $fullNameInput->setValidatorChain($fullNameValidator);
        
        $emailInput = new Input('email');
        $emailValidator = new ValidatorChain();
        $emailValidator->addValidator(new EmailAddress());
        $emailInput->setValidatorChain($emailValidator);
        
        $passwordInput = new Input('password');
        $passwordValidator = new ValidatorChain();
        $passwordValidator->addValidator(new NotEmpty());
        $passwordValidator->addValidator(new Alnum(array(
            'allowWhiteSpace' => true
        )));
        $passwordValidator->addValidator(new StringLength(array(
            'min' => 2,
            'max' => 32
        )));
        $passwordInput->setValidatorChain($passwordValidator);
        
        $birthdayInput = new Input('birthday');
        $birthdayValidator = new ValidatorChain();
        $birthdayValidator->addValidator(new \Zend\Validator\Date(array(
            'format' => 'dd/MM/yyyy',
            'locale' => 'vi_VN'
        )));
        $birthdayInput->setValidatorChain($birthdayValidator);
        
        $addressInput = new Input('address');
        $addressValidator = new ValidatorChain();
        $addressValidator->addValidator(new NotEmpty());
        $addressValidator->addValidator(new StringLength(array(
            'min' => 3,
            'max' => 32
        )));
        $addressInput->setValidatorChain($addressValidator);
        
        $phoneNumberInput = new Input('phoneNumber');
        $phoneNumberValidator = new ValidatorChain();
        $phoneNumberValidator->addValidator(new Digits());
        $phoneNumberValidator->addValidator(new StringLength(array(
            'min' => 8,
            'max' => 15
        )));
        $phoneNumberInput->setValidatorChain($phoneNumberValidator);
        
        // Input Filters
        $inputFilter = new InputFilter();
        $inputFilter->add($userIdInput)
            ->add($userGroupInput)
            ->add($fullNameInput)
            ->add($emailInput)
            ->add($passwordInput)
            ->add($birthdayInput)
            ->add($phoneNumberInput);
        
        return $inputFilter;
    }
}