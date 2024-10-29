<?php

/**
 * AjdtApiUtils class for managing APIs 
 */
class AjdtApiUtils extends WP_REST_Controller {
   
    /**
     * Constructor
     */
    public function __construct() {
        // Init REST API routes.
        $this->register_routes();
    }

  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes() { 
    register_rest_route( AJDT_API_NAMESPACE, AJDT_API_UTIL_BASE, array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_items' ),
        'permission_callback' => array( $this, 'get_items_permissions_check' ),
        'args'                => array(),
      ),
      array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => array( $this, 'create_item' ),
        'permission_callback' => array( $this, 'create_item_permissions_check' ),
        'args'                => $this->get_endpoint_args_for_item_schema( true ),
      ),
    ) );    // \w - string param, \d - digits 
    register_rest_route( AJDT_API_NAMESPACE, AJDT_API_UTIL_BASE . '/(?P<apiname>[\w]+)', array( 
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_item' ),
        'permission_callback' => array( $this, 'get_item_permissions_check' ),
        'args'                => array(
          'context' => array(
            'default' => 'view',
          ),
        ),
      ),
      array(
        'methods'             => WP_REST_Server::EDITABLE,
        'callback'            => array( $this, 'update_item' ),
        'permission_callback' => array( $this, 'update_item_permissions_check' ),
        'args'                => $this->get_endpoint_args_for_item_schema( false ),
      ),
      array(
        'methods'             => WP_REST_Server::DELETABLE,
        'callback'            => array( $this, 'delete_item' ),
        'permission_callback' => array( $this, 'delete_item_permissions_check' ),
        'args'                => array(
          'force' => array(
            'default' => false,
          ),
        ),
      ),
    ) );
    register_rest_route( AJDT_API_NAMESPACE, AJDT_API_UTIL_BASE . '/schema/(?P<table>[\w]+)', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => array( $this, 'get_key_column' ),
      'permission_callback' => array( $this, 'get_items_permissions_check' ),
    ) );
  }
 
   /**
   * Get Primay Column of a table
   *
   * @param Table Name.
   * @return WP_Error|WP_REST_Response
   */
  public function get_key_column( $request ) {
      $params = $request->get_params();
      $keys = ajdt_get_table_Key($params['table']);

    if(!$keys)
      return new WP_Error( 'no-data', __( 'No Primary Key found..!', 'text-domain' ), array( 'status' => 500 ) );

      return new WP_REST_Response($keys, 200 );
  }

  /**
   * Get a collection of items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_items( $request ) {
    $data = get_option(AJDT_APILISTNAME);
    return new WP_REST_Response($data, 200 );
  }
 
  /**
   * Get one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_item( $request ) {
    //get parameters from request
    $params = $request->get_params();
    $apiname = $params['apiname'];
    $apiList = get_option(AJDT_APILISTNAME);

    $data = $apiList[$apiname];
    if(!$data)
      return new WP_Error( 'no-data', __( 'No matching api found..!', 'text-domain' ), array( 'status' => 500 ) );

    return new WP_REST_Response( $data, 200 );
  }
 
  /**
   * Create one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function create_item( $request ) {
      $params = $request->get_params();

      if ( !isset( $params['name'] ) ) {
          return new WP_Error( 'cant-create', __( 'Api Name is required..', 'text-domain' ), array( 'status' => 500 ) );
      }

      if ( !isset( $params['primarykey'] ) ) {
          return new WP_Error( 'cant-create', __( 'Primarykey is required..', 'text-domain' ), array( 'status' => 500 ) );
      }

      if ( !isset( $params['table'] ) ) {
          return new WP_Error( 'cant-create', __( 'DB Table Name is required..', 'text-domain'), array( 'status' => 500 ) );
      }

      if ( !isset( $params['method'] ) ) {
          return new WP_Error( 'cant-create', __( 'Http Method(s) is required..', 'text-domain'), array( 'status' => 500 ) );
      }

      $apiName = sanitize_text_field( wp_unslash($params['name']));
      $primaryKey = sanitize_text_field( wp_unslash($params['primarykey']));
      $tableName = sanitize_text_field( wp_unslash($params['table']));
      $httpMethods = sanitize_text_field( wp_unslash($params['method']));

      $list = get_option(AJDT_APILISTNAME);
      $url = AJDT_API_NAMESPACE.'/'.$apiName;
      $list[$apiName] =  array(
                          "TableName" => $tableName,
                          "MethodName" => $httpMethods,
                          "PrimaryKey" => $primaryKey,
                          "SelectedColumn" => 'All',
                          "ConditionColumn" => '',
                          "SelectedCondtion" => 'no condition',
                          "SelectedParameter" => 1,
                          "Query" => 'Select * from '.$tableName.';',
                          "Url" => $url
                  );
      update_option(AJDT_APILISTNAME, $list);
      $data = get_option(AJDT_APILISTNAME);

      if ( is_array( $data ) ) {
        return new WP_REST_Response( $data, 200 );
      }
 
    return new WP_Error( 'cant-create', __( 'Unexpected exception happened..!', 'text-domain' ), array( 'status' => 500 ) );
  }
 
  /**
   * Update one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function update_item( $request ) {
    $params = $request->get_params();
    if ( !isset( $params['apiname'] ) ) {
        return new WP_Error( 'cant-create', __( 'API Name is required..', 'text-domain'), array( 'status' => 500 ) );
    }

    $apiname = sanitize_text_field( wp_unslash($params['apiname']));
    $apiList = get_option(AJDT_APILISTNAME);
    $itemToUpdate = $apiList[$apiname];

    if(!$itemToUpdate) {
      return new WP_Error( 'cant-update', __( 'No matching api found..!', 'text-domain' ), array( 'status' => 500 ) );
    }

    if ( !isset( $params['table'] ) ) {
        return new WP_Error( 'cant-create', __( 'DB Table Name is required..', 'text-domain'), array( 'status' => 500 ) );
    }

    if ( !isset( $params['cols'] ) ) {
        return new WP_Error( 'cant-create', __( 'SelectedColumn is required..', 'text-domain' ), array( 'status' => 500 ) );
    }

    if ( !isset( $params['method'] ) ) {
        return new WP_Error( 'cant-create', __( 'Http Method(s) required..', 'text-domain'), array( 'status' => 500 ) );
    }

    $tableName = sanitize_text_field( wp_unslash($params['table']));
    $cols = sanitize_text_field( wp_unslash($params['cols']));
    $httpMethods = sanitize_text_field( wp_unslash($params['method']));

    $itemToUpdate['TableName'] = $tableName;
    $itemToUpdate['MethodName'] = $httpMethods;
    $itemToUpdate['SelectedColumn'] = $cols;

    $apiList[$apiname] = $itemToUpdate;
    update_option(AJDT_APILISTNAME, $apiList);

    return new WP_REST_Response( $apiList, 200 );
  }
 
  /**
   * Delete one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function delete_item( $request ) {
    $params = $request->get_params();

    if ( !isset( $params['apiname'] ) ) {
        return new WP_Error( 'cant-create', __( 'API Name is required..', 'text-domain'), array( 'status' => 500 ) );
    }
    $apiname = sanitize_text_field( wp_unslash($params['apiname']));

    $apiList = get_option(AJDT_APILISTNAME);

    $itemToDel = $apiList[$apiname];
    if(!$itemToDel)
      return new WP_Error( 'cant-delete', __( 'No matching api found..!', 'text-domain' ), array( 'status' => 500 ) );

    unset($apiList[$apiname]);
    update_option(AJDT_APILISTNAME, $apiList);

    return new WP_REST_Response( $apiList, 200 );
  }
 
  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) {
    return current_user_can('administrator');
  }
 
  /**
   * Check if a given request has access to get a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_item_permissions_check( $request ) {
    return $this->get_items_permissions_check( $request );
  }
 
  /**
   * Check if a given request has access to create items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function create_item_permissions_check( $request ) {
    return current_user_can('administrator'); //current_user_can( 'edit_something' );
  }
 
  /**
   * Check if a given request has access to update a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function update_item_permissions_check( $request ) {
    return $this->create_item_permissions_check( $request );
  }
 
  /**
   * Check if a given request has access to delete a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function delete_item_permissions_check( $request ) {
    return $this->create_item_permissions_check( $request );
  }
 
  /**
   * Prepare the item for create or update operation
   *
   * @param WP_REST_Request $request Request object
   * @return WP_Error|object $prepared_item
   */
  protected function prepare_item_for_database( $request ) {
    return array();
  }
 
  /**
   * Prepare the item for the REST response
   *
   * @param mixed $item WordPress representation of the item.
   * @param WP_REST_Request $request Request object.
   * @return mixed
   */
  public function prepare_item_for_response( $item, $request ) {
    return array();
  }
 
  /**
   * Get the query params for collections
   *
   * @return array
   */
  public function get_collection_params() {
    return array(
      'page'     => array(
        'description'       => 'Current page of the collection.',
        'type'              => 'integer',
        'default'           => 1,
        'sanitize_callback' => 'absint',
      ),
      'per_page' => array(
        'description'       => 'Maximum number of items to be returned in result set.',
        'type'              => 'integer',
        'default'           => 10,
        'sanitize_callback' => 'absint',
      ),
      'search'   => array(
        'description'       => 'Limit results to those matching a string.',
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
      ),
    );
  }
}