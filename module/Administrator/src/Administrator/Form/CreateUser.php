<?php
namespace Administrator\Form;

use Blx\Form\AbstractForm;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Date;
use Zend\Form\Element\Hidden;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\ValidatorChain;
use Zend\Validator\Db\RecordExists;
use Zend\Validator\NotEmpty;
use Zend\I18n\Validator\Alpha;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\StringLength;

class CreateUser extends AbstractForm
{

    public function create()
    {
        $userId = new Hidden();
        $userId->setName('ID');
        
        $userGroup = new Select();
        $userGroup->setName('group');
        
        $fullName = new Text();
        $fullName->setName('fullName');
        
        $email = new Email();
        $email->setName('email');
        
        $password = new Password();
        $password->setName('password');
        
        $birthday = new Date();
        $birthday->setName('birthday');
        
        $address = new Text();
        $address->setName('address');
        
        $phoneNumber = new Text();
        $phoneNumber->setName('phoneNumber');
        
        $this->form->add($userId)
            ->add($userGroup)
            ->add($fullName)
            ->add($email)
            ->add($password)
            ->add($birthday)
            ->add($address)
            ->add($phoneNumber);
        
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
        $emailInput->setValidatorChain($emailValidator);
        
        $passwordInput = new Input('password');
        $birthdayInput = new Input('birthday');
        $addressInput = new Input('address');
        $phoneNumberInput = new Input('phoneNumber');
        
        // Input Filters
        $inputFilter = new InputFilter();
        $inputFilter->add($userIdInput)
            ->add($userGroupInput)
            ->add($fullNameInput)
            ->add($emailInput)
            ->add($passwordInput)
            ->add($birthdayInput)
            ->add($phoneNumberInput);
        
        $this->form->setInputFilter($inputFilter);
        
        return $this->form;
    }
}