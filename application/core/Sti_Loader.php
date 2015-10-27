<?php

/**
 * Classe Utilizada para extender as funcionalidades de loader do CodeIgniter 3
 * 
 * @package Core_Extensions
 * @author  Roger Risson <roger-risson@bm.rs.gov.br>
 * @license MIT License
 */
class Sti_Loader extends CI_Loader {

	/**
	 * Carrega uma view
	 *
	 * Este método substitui o antigo método *$this->load->view()* do CodeIgniter
	 * e adiciona suporte ao padrão de visão em dois passos, básicamente isto é
	 * feito pelo novo método introduzido **$layout**.
	 *
	 * **$layout** é utilizado para sinalizar que se deseja utilizar um layout e
	 * que o arquivo de visão *$view* deve ser embarcado dentro do arquivo 
	 * *$layout*.
	 *
	 * ### Exemplos
	 * 
	 * #### Carregar uma view utilizando as configurações de layout padrão:
	 * ```php
	 * // No contexto de um controlador
	 * $this->load->view('view_name');
	 * ```
	 * #### Enviando variáveis para a view:
	 * ```php
	 * // No contexto de um controlador
	 * $data = array(
	 *    'title'   => 'algum título',
	 *    'message' => 'alguma mensagem'
	 * );
	 * $this->load->view('view_name', $data);
	 * ```
	 * #### Definindo um layout para a view:
	 * ```php
	 * // No contexto de um controlador
	 * $this->load->view('view_name', array('title'=>'Meu Título!'), 'layout_name');
	 * ```
	 * #### Retirando o layout explicitamente:
	 * ```php
	 * // No contexto de um controlador
	 * $this->load->view('view_name', array('mesage'=>'Minha Mensagem!'), false);
	 * ```
	 * #### Utilizando o layout padrão definido no arquivo de configuração:
	 * ```php
	 * // No contexto de um controlador
	 * $this->load->view('view_name', $_POST, null);
	 * // OU...
	 * $this->load->view('view_name', $_POST);
	 * ```
	 * #### Retornando o processamento da view com seu layout para uma variável:
	 * ```php
	 * // No contexto de um controlador
	 * $resultado = $this->load->view('view_name', array('title'=>'Meu Título!'), 'layout', true);
	 * ```
	 * 
	 * @param  string       $view     O nome da View ("view" para um arquivo view.php)
	 * @param  array        $data     Os dados que serão passados para a View na forma
	 *                                de variáveis.
	 * @param  string|bool  $layout   O nome do Layout ("layout" para um arquivo layout.php),
	 *                                **false** define que não se quer utilizar layout e **null**
	 *                                define que se deseja utilizar o valor da opção "default_layout"
	 *                                do arquivo de configuração.
	 * @param  boolean      $return  Retorna o resultado do processamento da view? 
	 *                                (**true** = retorna; **false** = mostra)
	 * @return mixed                  Retorna os dados processados da View e do Layout se este
	 *                                tiver sido definido. Os dados apenas são retornados se
	 *                                $return estiver setado para **true**.
	 */
	public function view($view, $data=array(), $layout=null, $return=false)
	{

		if ($layout === false) {

			return parent::view( $view, $data, $return );

		} else if (is_null($layout)) {

			$CI =& get_instance();
			$layout = $CI->config->item('default_layout');

			$data['_BODY'] = parent::view($view, $data, true);
			
			if (is_string($layout)) {
				return parent::view( $layout , $data, $return);
			} else if ($layout == false) {
				if ($return) {
					return $data['_BODY'];
				} else {
					$this->output->append_output($data['_BODY']);
				}
			}

		} else {

			$data['_BODY'] = parent::view($view, $data, true);
			return parent::view( $layout , $data, $return);
			
		}
	}
}