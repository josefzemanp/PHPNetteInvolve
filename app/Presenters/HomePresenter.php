<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form; 
use Nette\Utils\Html\Table;

class Person {
    public $firstName;
    public $lastName;
    public $position;

    public function __construct($firstName, $lastName, $position) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->position = $position;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getPosition() {
        return $this->position;
    }
}
class Team {
    public $teamName;
    public array $members;

    public function __construct($teamName, $members) {
        $this->teamName = $teamName;
        $this->members = $members;
    }

    public function getTeamName() {
        return $this->teamName;
    }

    public function getMembers() {
        return $this->members;
    }
}

final class HomePresenter extends Nette\Application\UI\Presenter
{

    private array $people = [];

    public function renderDefault(): void
    {
        $this->template->people = $this->people;
    }

    public function addPerson($firstName, $lastName, $position)
    {
        $person = new Person($firstName, $lastName, $position);
        array_push($this->people, $person);
        $this->template->people = $this->people;
    }

	protected function createComponentRegistrationForm1(): Form
	{
        $positions = ['Závodník', 'Technik', 'Manažer', 'Spolujezdec', 'Fotograf'];
            $form1 = new Form;
            $form1->addText('firstName', 'Jméno:');
            $form1->addText('lastName', 'Příjmení:');
            $form1->addSelect('position', 'Pozice:', $positions)
            ->setPrompt('Vyber pozici');
            $form1->addSubmit('submit', 'Registrovat');
            $form1->onSuccess[] = [$this, 'formSucceeded1'];

            return $form1;
    
        }

        
	    protected function createComponentRegistrationForm2(): Form
	    {
            $users = $this->people;

            $form2 = new Form;
            $form2->addText('teamName', 'Název týmu:');

            $form2->addMultiSelect('items', 'Lidé:', $users);
            $form2->addSubmit('submit', 'Registrovat');
            $form2->onSuccess[] = [$this, 'formSucceeded2'];

            return $form2;
    
        }
    
        public function formSucceeded1(Form $form, $data): void
        {    
            $this->addPerson($data->firstName, $data->lastName, $data->position);
            $this['registrationForm2']['items']->setItems($this->people);
            $this->redirect('Home:');
        }

        public function formSucceeded2(Form $form, $data): void
        {
            $this->redirect('Home:');
        }

    }