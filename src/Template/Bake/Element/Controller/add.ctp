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
$compact = ["'" . $singularName . "'"];
%>

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $error = array();
        $message = '';
        $success = 0;
        $<%= $singularName %> = $this-><%= $currentModelName %>->newEntity($this->request->data);
        if ($<%= $singularName %>->errors()) {
            $error = $<%= $singularName %>->errors();
        } else {
            if ($this-><%= $currentModelName; %>->save($<%= $singularName %>)) {
                $success = 1;
                $message = 'The <%= strtolower($singularHumanName) %> has been saved.';
            } else {
                $message = 'The <%= strtolower($singularHumanName) %> could not be saved. Please, try again.';
            }
        }

        $this->set([
            'message' => $message,
            'error' => $error,
            'success' => $success,
            'data' => $<%= $singularName %>,
            '_serialize' => ['message', 'data', 'success', 'error']
        ]);
    }
