<?php

namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tropa\Form\Setor as SetorForm;
use Tropa\Model\Setor;

class SetorController extends AbstractActionController
{

    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel(
            ['models' => $this->table->fetchAll()]
        );
    }

    /**
     * Action to add and change records
     */
    public function editAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $setor = $this->table->getModel($codigo);
        $form = new SetorForm();
        $form->get('submit')->setValue(
            empty($codigo) ? 'Cadastrar' : 'Alterar'
        );
        $form->bind($setor);
        return ['form' => $form];
    }

    /**
     * Action to save a record
     */
    public function saveAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new SetorForm();
            $setor = new Setor();
            $form->setInputFilter($setor->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $setor->exchangeArray($form->getData());
                $this->table->saveModel($setor);
            }
        }

        return $this->redirect()->toRoute(
            'tropa',
            [
                'controller' => 'setor',
                'action' => 'index'
            ]
        );
    }

    /**
     * Action to remove records
     */
    public function deleteAction()
    {
        $codigo = $this->params()->fromRoute('key', null);
        $this->table->deleteModel($codigo);

        return $this->redirect()->toRoute(
            'tropa',
            [
                'controller' => 'setor',
                'action' => 'index'
            ]
        );
    }
}
