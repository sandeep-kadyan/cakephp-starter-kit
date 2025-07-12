<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AuthRequests Controller
 *
 * @property \App\Model\Table\AuthRequestsTable $AuthRequests AuthRequestsTable
 */
class AuthRequestsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->AuthRequests->find();
        $authRequests = $this->paginate($query);

        $this->set(compact('authRequests'));
    }

    /**
     * View method
     *
     * @param string|null $id Auth Request id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $authRequest = $this->AuthRequests->get($id, contain: []);
        $this->set(compact('authRequest'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $authRequest = $this->AuthRequests->newEmptyEntity();
        if ($this->request->is('post')) {
            $authRequest = $this->AuthRequests->patchEntity($authRequest, $this->request->getData());
            if ($this->AuthRequests->save($authRequest)) {
                $this->Flash->success(__('The auth request has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The auth request could not be saved. Please, try again.'));
        }
        $this->set(compact('authRequest'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Auth Request id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $authRequest = $this->AuthRequests->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $authRequest = $this->AuthRequests->patchEntity($authRequest, $this->request->getData());
            if ($this->AuthRequests->save($authRequest)) {
                $this->Flash->success(__('The auth request has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The auth request could not be saved. Please, try again.'));
        }
        $this->set(compact('authRequest'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Auth Request id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $authRequest = $this->AuthRequests->get($id);
        if ($this->AuthRequests->delete($authRequest)) {
            $this->Flash->success(__('The auth request has been deleted.'));
        } else {
            $this->Flash->error(__('The auth request could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
