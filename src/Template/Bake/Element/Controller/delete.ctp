<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
%>

    /**
     * Delete method
     *
     * @param string|null $id <%= $singularHumanName %> id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $<%= $singularName %> = $this-><%= $currentModelName %>->get($id);
        if ($this-><%= $currentModelName; %>->delete($<%= $singularName %>)) {
             $message = 'The <%= strtolower($singularHumanName) %> has been deleted.';
            $success = 1;
        } else {
             $message = 'The <%= strtolower($singularHumanName) %> could not be deleted. Please, try again.';
             $success = 0;
        }
        $this->set([
            'message' => $message,
            'success' => $success,
            '_serialize' => ['message', 'success']
        ]);
    }

