<?php
	
	require_once('lib/usersdatasource.php');
	
	Class Extension_DS_Users extends Extension {
		public function about() {
			return array(
				'name'			=> 'Users',
				'version'		=> '1.0.0',
				'release-date'	=> '2010-02-26',
				'type'			=> array(
					'Data Source', 'Core'
				),
				'author'		=> array(
					'name'			=> 'Symphony Team',
					'website'		=> 'http://symphony-cms.com/',
					'email'			=> 'team@symphony-cms.com'
				),
				'provides'		=> array(
					'datasource_template'
				),
				'description'	=> 'Create data sources from backend user data.'
			);
		}
		
		public function prepare(array $data=NULL) {
			
			$datasource = new UsersDataSource;
			
			if(!is_null($data)){
				if(isset($data['about']['name'])) $datasource->about()->name = $data['about']['name'];
				if(isset($data['included-elements'])) $datasource->parameters()->{'included-elements'} = $data['included-elements'];
				
				if(isset($data['filters']) && is_array($data['filters'])){
					foreach($data['filters'] as $handle => $value){
						$datasource->parameters()->filters[$handle] = $value;
					}
				}
			}
			
			return $datasource;
		}

		public function view(Datasource $datasource, XMLElement &$wrapper, MessageStack $errors) {

		//	Essentials --------------------------------------------------------
			
			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', __('Essentials')));
			
			// Name:
			$label = Widget::Label(__('Name'));
			$input = Widget::Input('fields[about][name]', General::sanitize($datasource->about()->name));
			$label->appendChild($input);

			if (isset($errors->{'about::name'})) {
				$label = Widget::wrapFormElementWithError($label, $errors->{'about::name'});
			}

			$fieldset->appendChild($label);
			$wrapper->appendChild($fieldset);

		//	Filtering ---------------------------------------------------------

			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', __('Filtering')));
			$p = new XMLElement('p', __('{$param} or Value'));
			$p->setAttribute('class', 'help');
			$fieldset->appendChild($p);

			$div = new XMLElement('div');
			$h3 = new XMLElement('h3', __('Filter Users by'));
			$h3->setAttribute('class', 'label');
			$div->appendChild($h3);

			$ol = new XMLElement('ol');
			$ol->setAttribute('class', 'filters-duplicator');

			$this->appendFilter($ol, __('ID'), 'id', $datasource->parameters()->filters['id']);
			$this->appendFilter($ol, __('Username'), 'username', $datasource->parameters()->filters['username']);
			$this->appendFilter($ol, __('First Name'), 'first-name', $datasource->parameters()->filters['first-name']);
			$this->appendFilter($ol, __('Last Name'), 'last-name', $datasource->parameters()->filters['last-name']);
			$this->appendFilter($ol, __('Email Address'), 'email-address', $datasource->parameters()->filters['email-address']);

			$div->appendChild($ol);

			$fieldset->appendChild($div);
			$wrapper->appendChild($fieldset);

		//	Output options ----------------------------------------------------
			
			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', __('Output Options')));
	
			$ul = new XMLElement('ul');
			$ul->setAttribute('class', 'group');
			
			$li = new XMLElement('li');
			$li->appendChild(new XMLElement('h3', __('XML Output')));
			
			$select = Widget::Select('fields[included-elements][]', array(
				array('username', in_array('username', $datasource->parameters()->{"included-elements"}), 'username'),
				array('name', in_array('name', $datasource->parameters()->{"included-elements"}), 'name'),
				array('email-address', in_array('email-address', $datasource->parameters()->{"included-elements"}), 'email-address'),
				array('authentication-token', in_array('authentication-token', $datasource->parameters()->{"included-elements"}), 'authentication-token'),
				array('default-section', in_array('default-section', $datasource->parameters()->{"included-elements"}), 'default-section'),	
				array('formatting-preference', in_array('formatting-preference', $datasource->parameters()->{"included-elements"}), 'formatting-preference')
			));
			$select->setAttribute('class', 'filtered');
			$select->setAttribute('multiple', 'multiple');
			
			$label = Widget::Label(__('Included Elements'));
			$label->appendChild($select);
			$li->appendChild($label);
			$ul->appendChild($li);
			
			$fieldset->appendChild($ul);
			$wrapper->appendChild($fieldset);
		}
		
		protected function appendFilter(&$wrapper, $name, $handle, $value=NULL) {
			if (!is_null($value)) {
				$li = new XMLElement('li');
				$li->setAttribute('class', 'unique');
				$li->appendChild(new XMLElement('h4', $name));
				$label = Widget::Label(__('Value'));
				$label->appendChild(Widget::Input(
					'fields[filters][' . $handle . ']',
					General::sanitize($value)
				));
				$li->appendChild($label);
			 	$wrapper->appendChild($li);	
			}
			
			$li = new XMLElement('li');
			$li->setAttribute('class', 'unique template');
			$li->appendChild(new XMLElement('h4', $name));		
			$label = Widget::Label(__('Value'));
			$label->appendChild(Widget::Input('fields[filters][' . $handle . ']'));
			$li->appendChild($label);
		 	$wrapper->appendChild($li);
		}
	}