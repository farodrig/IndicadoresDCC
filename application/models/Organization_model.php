<?php

class Organization_model extends CI_Model {

	public $title;

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->title = "Organization";
	}

	/*
	Entrega las raices del arbol de organizaciones. El DCC tipicamente se divide en 2 arboles, Operación y Soporte.
	Si no se obtienen estas dos raices entrega false, a modo de error.
	Sino, entrega un arreglo con ambas raices.
	 */
	function getDepartment() {
		$this->db->where("id = parent");
		$query = $this->db->get($this->title);
		return ($query->num_rows() != 2) ? false : $this->buildAllOrganization($query);
	}

	/*
	Añade un area a la BD. De los posibles padres (root) se relaciona con aquel que tiene el mismo tipo y se guarda.
	$data debe tener un 'type' y un 'name'.
	En caso de error entrega False.
	 */
	function addArea($data) {
		$root = $this->getDepartment();
		foreach ($root as $r) {
			if ($r->getType() == $data['type']) {
				return $this->addChild($r->getId(), $data);
			}
		}
		return false;
	}

	/*
	Añade un unidad a la BD dado un padre y la información de la unidad.
	$parentId debe ser el Id del padre de la unidad, osea el area a la cual pertenece.
	$data debe tener un 'name'.
	En caso de error entrega False.
	 */
	function addUnidad($parentId, $data) {
		$parent       = $this->getByID($parentId);
		$data['type'] = $parent->getType();
		return $this->addChild($parentId, $data);
	}

	/*
	Añade un hijo a un elemento del arbol.
	$parent debe el id del padre del elemento que se quiere agregar.
	$data debe tener un 'type' y un 'name'.
	En caso de error entrega False.
	 */
	private function addChild($parent, $data) {
		$data['parent'] = $parent;
		return ($this->db->insert($this->title, $data)) ? true : false;
	}

	/*
	Entrega todos los tipos que existen en OrgType.
	 */
	function getTypes() {
		return $this->getTypesWhere(array());
	}

	/*
	Entrega todos los tipos que existen en OrgType que posean el nombre $name.
	$name debe ser un String.
	 */
	function getTypeByName($name) {
		return $this->getTypesWhere(array('name' => $name));
	}

	/*
	Entrega todos el tipo que exista en OrgType con el ID $id.
	$id debe ser un número entero.
	 */
	function getTypeById($id) {
		return $this->getTypesWhere(array('id' => $id));
	}

	/*
	Entrega todos los tipos que existen en OrgType filtrados por $where.
	$where debe ser un arreglo asociativo.
	Retorna un arreglo con los tipos solicitados. En caso de que sea solo uno, retorna el elemento y no un arreglo.
	 */
	private function getTypesWhere($where) {
		$this->db->where(array('name!=' => ""));
		if (count($where) != 0) {
			$this->db->where($where);
		}

		$query   = $this->db->get('OrgType');
		$result  = array();
		$colores = array('Soporte' => '#47a447', 'soporte' => '#47a447', 'Operación' => '#ed9c28', 'operación' => '#ed9c28');
		foreach ($query->result() as $row) {
			array_push($result, array('id' => $row->id, 'name' => $row->name, 'color' => $colores[$row->name]));
		}
		return (count($result) == 1) ? $result[0] : array_reverse($result);
	}

	/*
	Entrega todas las unidades que pertenecen a una área específica.
	$area debe ser el id del área.
	Retorna un arreglo con las unidades correspondientes.
	 */
	function getAllUnidades($area) {
		return $this->getAllChilds($area);
	}

	/*
	Entrega todas las áreas de la BD.
	Retorna un arreglo con las áreas correspondientes.
	 */
	function getAllAreas() {
		$root = $this->getDepartment();
		$res  = [];
		foreach ($root as $key) {
			$res = array_merge($res, $this->getAllChilds($key->getId()));
		}
		return $res;
	}

	/*
	Entrega todas los hijos dado el id del padre.
	$id debe ser un entero.
	Retorna un arreglo con los hijos correspondientes.
	 */
	function getAllChilds($id) {
		$this->db->where(array('parent' => $id));
		$this->db->where('id!=parent');
		$query = $this->db->get($this->title);
		return $this->buildAllOrganization($query);
	}

	/*
	Dado el id de un elemento, este es eliminado de la BD.
	$id debe ser un integer con el id del elemento a eliminar.
	Retorna un booleano indicando si se logró eliminar o no.
	 */
	function delById($id) {
		$this->db->where(array('id' => $id));
		$query = $this->db->delete($this->title);
		return ($this->db->affected_rows() == 0) ? false : true;
	}

	/*
	Entrega un elemento segun el id entregado.
	$id debe ser un entero.
	Retorna un objeto con los campos correspondientes.
	 */
	function getByID($id) {
		$this->db->where(array('id' => $id));
		$query = $this->db->get($this->title);
		return ($query->num_rows() != 1) ? false : $this->buildOrganization($query->row());
	}

	/*
	Entrega un elemento segun el nombre entregado.
	$name debe ser un string.
	Retorna un objeto con los campos correspondientes.
	 */
	function getByName($name) {
		$this->db->where(array('name' => $name));
		$query = $this->db->get($this->title);
		return ($query->num_rows() != 1) ? false : $this->buildOrganization($query->row());
	}

	/*
	Construye un arreglo con objetos dados en una query $q.
	$q debe ser un query del sistema.
	Retorna un arreglo con los objetos con los campos correspondientes.
	 */
	function buildAllOrganization($q) {
		$orgs = array();
		foreach ($q->result() as $row) {
			array_push($orgs, $this->buildOrganization($row));
		}
		return $orgs;
	}

	/*
	Construye un objeto con los campos respectivos a la tabla Organization dada una fila de una query.
	$row debe ser un fila entregada por una query del sistema.
	Retorna un objeto con los campos correspondientes.
	 */
	function buildOrganization($row) {
		$this->load->library('Organization_library');
		$parameters = array(
			'id'     => $row->id,
			'parent' => $row->parent,
			'type'   => $row->type,
			'name'   => $row->name
		);
		$org = new Organization_library();
		return $org->initialize($parameters);
	}
}
