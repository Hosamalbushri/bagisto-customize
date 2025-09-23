<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Delivery Agents Section
    |--------------------------------------------------------------------------
    */

    'deliveryAgent' => [

        'menu' => [
            'title'             => 'Delivery Services',
            'delivery-agents'   => 'Delivery Agents',
        ],

        'acl' => [
            'title'             => 'Delivery Services',
            'delivery-agents'   => 'Delivery Agents',
            'create'            => 'Create',
            'edit'              => 'Edit',
            'delete'            => 'Delete',
        ],
        'system' => [
            'title'    => 'Delivery Management',
            'info'     => 'Comprehensive settings for delivery service',

            'settings' => [
                'title'    => 'Delivery Settings',
                'info'     => 'Manage and configure delivery service settings',

                'general'  => [
                    'title'  => 'General Settings',
                    'info'   => 'Configure basic settings for delivery system',
                    'fields' => [
                        'enable-delivery-system'=> 'Enable Delivery Service',
                    ],
                ],

                'store' => [
                    'title'      => 'Store Settings',
                    'store-info' => 'Configure store-related settings',
                    'fields'     => [
                        'default-country' => 'Default Store Country',
                    ],
                ],

                'agent' => [
                    'title'  => 'Agent Settings',
                    'info'   => 'Control general settings for delivery agents',
                    'fields' => [
                        // Future settings like "Maximum orders per agent" can be added here
                    ],
                ],

                'ranges' => [
                    'title'  => 'Delivery Range Settings',
                    'info'   => 'Control how delivery ranges are set for agents',
                    'fields' => [
                        'allow-multiple-ranges' => 'Allow multiple delivery ranges per agent',
                    ],
                ],

                'orders' => [
                    'title'  => 'Order Settings',
                    'info'   => 'Manage settings for delivery orders',
                    'fields' => [
                        'allow_agent_acceptance'=> 'Allow delivery agents to accept or reject orders',
                        'show_agent_data_to_customer'=> 'Show agent data to customer (name and phone)',
                        'allow_agent_rating'=> 'Allow rating of delivery agents',
                    ],
                ],
            ],
        ],

        'index' => [
            'title' => 'Delivery Agents List',
        ],

        'view' => [
            'title'         => 'Agent',
            'back-btn'      => 'Back to List',
            'delivery-agent'=> 'Agent Name',
            'first-name'    => 'First Name',
            'last-name'     => 'Last Name',
            'phone'         => 'Contact Number - :phone',
            'email'         => 'Email - :email',
            'date-of-birth' => 'Date of Birth - :dob',
            'gender'        => 'Gender - :gender',
            'status'        => 'Current Status',
            'active'        => 'Active',
            'inactive'      => 'Inactive',
            'edit-btn'      => 'Edit',
            'dataGrid'      => [
                'orders'        => [
                    'count'      => 'Orders (:count)',
                    'empty-order'=> 'No orders found',
                ],
            ],

        ],

        'orders'=> [
            'view'=> [
                'accepted-order-confirmation'        => 'Are you sure you want to accept this order?',
                'rejected-order-confirmation'        => 'Are you sure you want to reject this order?',
                'out-for-delivery-order-confirmation'=> 'Are you sure you want to change the order status to "Out for Delivery"? After confirmation, you cannot modify the order status.',
                'delivered-order-confirmation'       => 'Are you sure you want to change the order status to "Delivered"? After confirmation, the order will be closed and cannot be modified.',
            ],
            'status'=> [
                'assigned_to_agent'   => 'New Order',
                'accepted_by_agent'   => 'Accepted Order',
                'rejected_by_agent'   => 'Rejected Order',
                'out_for_delivery'    => 'Out for Delivery',
                'delivered'           => 'Delivered',
            ],
            'actions'=> [
                'accept_btn'          => 'Accept',
                'reject_btn'          => 'Reject',
                'out_for_delivery_btn'=> 'Deliver',
                'delivered_btn'       => 'Complete',
            ],
            'acl'=> [
                'accept'          => 'Accept Order',
                'reject'          => 'Reject Order',
                'out_for_delivery'=> 'Deliver Order',
                'delivered'       => 'Complete Order',
            ],
        ],

        'create' => [
            'title'             => 'Add New Delivery Agent',
            'create-btn'        => 'Save Data',
            'create'            => 'Add Agent',
            'first-name'        => 'First Name',
            'last-name'         => 'Last Name',
            'phone'             => 'Phone Number',
            'email'             => 'Email',
            'date-of-birth'     => 'Date of Birth',
            'gender'            => 'Gender',
            'select-gender'     => 'Select Gender',
            'male'              => 'Male',
            'female'            => 'Female',
            'other'             => 'Other',
            'password'          => 'New Password',
            'confirm-password'  => 'Confirm Password',
            'status'            => 'Account Status',
            'select-status'     => 'Select Status',
            'active'            => 'Active',
            'inactive'          => 'Temporarily Disabled',
            'create-success'    => 'Agent added successfully.',
        ],

        'delete' => [
            'successful_deletion_message'   => 'Agent deleted successfully.',
            'unsuccessful_deletion_message' => 'Failed to delete agent. Please try again later.',
        ],

        'edit' => [
            'title'                       => 'Edit Agent Information',
            'edit-btn'                    => 'Save Changes',
            'save-btn'                    => 'Update',
            'edit-success'                => 'Agent information updated successfully.',
            'first-name'                  => 'First Name',
            'last-name'                   => 'Last Name',
            'phone'                       => 'Phone Number',
            'email'                       => 'Email',
            'date-of-birth'               => 'Date of Birth',
            'gender'                      => 'Gender',
            'select-gender'               => 'Select Gender',
            'male'                        => 'Male',
            'female'                      => 'Female',
            'other'                       => 'Other',
            'current_password'            => 'Current Password',
            'password'                    => 'New Password',
            'confirm-password'            => 'Confirm Password',
            'status'                      => 'Account Status',
            'select-status'               => 'Select Status',
            'active'                      => 'Active',
            'inactive'                    => 'Temporarily Disabled',
            'incorrect_current_password'  => 'Current password does not match',

        ],

        'dataGrid' => [
            'id'                => 'ID',
            'id-value'          => 'ID - :id',
            'name'              => 'Agent Name',
            'phone'             => 'Phone Number',
            'email'             => 'Email',
            'gender'            => 'Gender',
            'status'            => 'Status',
            'active'            => 'Active',
            'inactive'          => 'Inactive',
            'range-count'       => 'Range Count',
            'order_count'       => 'Order Count',
            'has_orders'        => 'Has Orders',
            'no_orders'         => 'No Orders',
            'no-order'          => 'No Orders',
            'range'             => ':range range(s)',
            'order'             => ':order order(s)',
            'country'           => 'Country',
            'state'             => 'CountryState',
            'actions'           => [
                'view'  => 'View',
                'delete'=> 'Delete',
            ],
            'delete'                        => 'Delete',
            'update-status'                 => 'Update Status',
            'delete-success'                => 'Agent deleted successfully',
            'update-success'                => 'Status updated successfully',
            'unsuccessful_deletion_message' => 'Cannot delete agent due to incomplete orders.',

            'orders'        => [
                'status'=> [
                    'assigned_to_agent'   => 'New Orders',
                    'accepted_by_agent'   => 'Accepted Orders',
                    'rejected_by_agent'   => 'Rejected Orders',
                    'out_for_delivery'    => 'Orders in Delivery',
                    'delivered'           => 'Completed Orders',
                ],
            ],

        ],

        'form' => [
            'name'      => 'Full Name',
            'phone'     => 'Phone Number',
            'email'     => 'Email',
            'password'  => 'Password',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Areas (Cities and States) Section
    |--------------------------------------------------------------------------
    */

    'country' => [

        'menu' => [
            'title'     => 'Delivery Ranges',
            'countries' => 'Countries',
        ],

        'acl' => [
            'title'         => 'Delivery Ranges',
            'countries'     => 'Countries',
            'create'        => 'Create',
            'edit'          => 'Edit',
            'delete'        => 'Delete',
        ],

        'dataGrid' => [
            'id'            => 'ID',
            'name'          => 'Country Name',
            'code'          => 'Country Code',
            'states_count'  => 'States Count',
            'actions'       => [
                'view'  => 'View',
                'delete'=> 'Delete',
            ],
            'delete-success'             => 'Deleted successfully',
            'no-found'                   => 'Delete failed',
            'mass-delete-success'        => 'Selected countries deleted successfully',
            'delete_warning_has_children'=> 'Cannot delete country because it contains children.',
        ],

        'index' => [
            'title' => 'Available Countries List',
        ],

        'create' => [
            'title'             => 'Add New Country',
            'name'              => 'Country Name',
            'code'              => 'Country Code',
            'create-btn'        => 'Save',
            'index-create-btn'  => 'Add Country',
            'create-success'    => 'Country added successfully.',
        ],

        'view' => [
            'title'               => 'Country Details',
            'back-btn'            => 'Back',
            'delete-btn'          => 'Delete Country',
            'country'             => 'Country',
            'name'                => 'Country Name',
            'code'                => 'Country Code',

            'states' => [
                'count'         => 'States Count :count',
                'create-btn'    => 'Add New CountryState',
            ],
        ],

        'edit' => [
            'title'         => 'Edit Country Information',
            'name'          => 'Country Name',
            'code'          => 'Country Code',
            'edit-btn'      => 'Edit',
            'edit-success'  => 'Country information updated successfully.',
        ],

        'state' => [
            'acl' => [
                'states'        => 'States',
                'view'          => 'View',
                'create'        => 'Create',
                'edit'          => 'Edit',
                'delete'        => 'Delete',
            ],
            'create' => [
                'title'             => 'Add New CountryState',
                'name'              => 'CountryState Name',
                'code'              => 'CountryState Code',
                'create-btn'        => 'Save CountryState',
                'create-success'    => 'CountryState saved successfully.',
            ],
            'view' => [
                'title'                => 'CountryState Details',
                'back-btn'             => 'Back',
                'delete-btn'           => 'Delete CountryState',
                'state'                => 'CountryState',
                'create-area-btn'      => 'Add City - Residential Area',
                'name'                 => 'CountryState Name',
                'code'                 => 'CountryState Code',
            ],
            'edit' => [
                'title'         => 'Edit CountryState Information',
                'name'          => 'CountryState Name',
                'code'          => 'CountryState Code',
                'edit-btn'      => 'Edit',
                'edit-success'  => 'CountryState information updated successfully.',
            ],

            'dataGrid' => [
                'id'                => 'ID',
                'name'              => 'CountryState Name',
                'code'              => 'CountryState Code',
                'actions'           => [
                    'view'  => 'View',
                    'delete'=> 'Delete',
                ],
                'delete-success'             => 'CountryState deleted successfully',
                'no-found'                   => 'CountryState deletion failed',
                'mass-delete-success'        => 'Selected states deleted successfully',
                'delete_warning_has_children'=> 'Cannot delete state because it contains children.',

            ],
            'area'=> [
                'create'=> [
                    'title'         => 'Add New City / Area',
                    'name'          => 'City or Area Name',
                    'save-btn'      => 'Save',
                    'create-success'=> 'City added successfully',

                ],
                'edit'=> [
                    'title'         => 'Edit City or Area',
                    'name'          => 'City or Area Name',
                    'edit-btn'      => 'Edit',
                    'edit-success'  => 'Updated successfully',
                ],
                'view'=> [
                    'title'   => 'Agents List',
                    'add-btn' => 'Add Agent',
                    'drawer'  => [
                        'header'=> 'Add Agents to',
                    ],
                    'dataGrid'=> [
                        'delete-from-area'    => 'Remove from Area',
                        'add-to-area'         => 'Add to Area',
                        'add-success'         => 'Selected agents added successfully to this area',
                        'deleted-success'     => 'Selected agents removed from this area',

                    ],

                ],
                'dataGrid'=> [
                    'id'                   => 'ID',
                    'name'                 => 'City or Area Name',
                    'delivery-count'       => 'Agents Count',
                    'actions'              => [
                        'edit'  => 'Edit',
                        'delete'=> 'Delete',
                    ],
                    'delete-success'             => 'Deleted successfully',
                    'no-found'                   => 'Delete operation failed',
                    'delete_warning_has_children'=> 'Cannot delete city because it contains children.',

                ],
                'acl'=> [
                    'areas'         => 'Cities or Areas',
                    'view'          => 'View',
                    'create'        => 'Create',
                    'edit'          => 'Edit',
                    'delete'        => 'Delete',
                ],
            ],
        ],
    ],
    /*
   |--------------------------------------------------------------------------
   | Agent Ranges and Areas Section
   |--------------------------------------------------------------------------
   */
    'range'=> [
        'index' => [
            'title' => 'Available Cities List',

        ],
        'acl'=> [
            'title'         => 'Agent Ranges',
            'create'        => 'Create',
            'edit'          => 'Edit',
            'delete'        => 'Delete',
        ],
        'create' => [
            'title'                      => 'Add New Range',
            'area-name'                  => 'City or Area',
            'country'                    => 'Country',
            'state'                      => 'CountryState',
            'create-btn'                 => 'Save Range',
            'index-create-btn'           => 'Add',
            'select_country'             => 'Select Country',
            'select_state'               => 'Select CountryState',
            'select_state_area'          => 'Select City or Area',
            'add_state'                  => 'Add CountryState',
            'add_area'                   => 'Add City or Area',
            'no_states_for_country'      => 'No states found for this country. Please select a country with states or add a new state.',
            'no_areas_for_state'        => 'No cities or areas found for this state. Please select a state with cities or areas or add a new city or area.',
            'create-success'             => 'Range added to agent successfully.',
            'create-failed'              => 'This agent is already registered in this geographical area.',
            'multiple-not-allowed'       => 'Cannot add more than one range for this agent.',
        ],
        'view'=> [
            'count'                     => 'Ranges Count (:count)',
            'empty-title'               => 'Add Range to Agent',
            'empty-description'         => 'Create new ranges for the agent',
            'delete-btn'                => 'Delete',
            'range-delete-confirmation' => 'Are you sure you want to delete this range?',
            'range-delete-success'      => 'Range deleted successfully',
            'range-delete-failed'       => 'Delete failed, please try again later',
        ],
        'edit'=> [
            'title'                      => 'Edit Current Range',
            'area-name'                  => 'City or Area',
            'country'                    => 'Country',
            'state'                      => 'CountryState',
            'view-edit-btn'              => 'Edit',
            'edit-btn'                   => 'Update',
            'index-create-btn'           => 'Add',
            'select_country'             => 'Select Country',
            'select_state'               => 'Select CountryState',
            'select_state_area'          => 'Select City or Area',
            'add_state'                  => 'Add CountryState',
            'add_area'                   => 'Add City or Area',
            'no_states_for_country'      => 'No states found for this country. Please select a country with states or add a new state.',
            'no_areas_for_state'        => 'No cities or areas found for this state. Please select a state with cities or areas or add a new city or area.',
            'edit-success'               => 'Range information updated successfully.',
            'edit-failed'                => 'This agent is already registered in this geographical area',

        ],

    ],

    /*
||--------------------------------------------------------------------------
|| Orders Section
||--------------------------------------------------------------------------
*/
    'select-order'=> [
        'index'=> [
            'select-delivery-agent-btn'     => 'Assign Delivery Agent',
            'reselect-delivery-agent-btn'   => 'Reassign Delivery Agent',
            'select-delivery-agent'         => 'Assign Agent to Order #',
            'assign-btn'                    => 'Assign',
            'assigning'                     => 'Assigning Agent',
            'processing'                    => 'Processing',
            'please-wait'                   => 'Please wait while we process your request',
            'in-progress'                   => 'In Progress',
            'tabs'                          => [
                'in-the-same-area'=> 'Available in :city',
                'all'             => 'All Agents',
            ],
            'assign-delivery-agent-confirmation'=> 'Are you sure you want to assign this agent to this order?',
        ],
        'create'=> [
            'create-success'    => 'Agent assigned successfully',
            'create-error'      => 'Sorry, the agent is not active. Please activate the agent first.',
            'order-has-delivery'=> 'A delivery agent has already been assigned to this order',
        ],
        'update'=> [
            'update-failed' => 'Sorry, you cannot modify the order status',
            'update-success'=> 'Order status updated successfully',
            'updated-error' => 'An error occurred during modification. Please check the agent status',
        ],
    ],
    'orders'=> [
        'view'=> [
            'delivery'                       => 'Delivery Agent',
            'no-delivery-agent-found'        => 'No delivery agent assigned to this order',
            'view'                           => 'View',
            'contact'                        => 'Contact',
            'item-delivered'                 => 'Delivered (:qty_delivered)',

        ],
        'acl' => [
            'title'             => 'Orders',
            'select-delivery'   => 'Assign Agent to Order',
            'create'            => 'Create',
            'edit'              => 'Edit',
            'delete'            => 'Delete',
        ],
        'status' => [
            'pending'             => 'Pending',
            'pending_payment'     => 'Pending Payment',
            'processing'          => 'Processing',
            'completed'           => 'Completed',
            'canceled'            => 'Canceled',
            'closed'              => 'Closed',
            'fraud'               => 'Fraud',
            'assigned_to_agent'   => 'Agent Assigned',
            'accepted_by_agent'   => 'Order Accepted',
            'rejected_by_agent'   => 'Order Rejected',
            'out_for_delivery'    => 'Out for Delivery',
            'delivered'           => 'Delivered',
        ],
    ],
    'notifications'=> [
        'order-status-messages'=> [

            'assigned_to_agent'=> 'Agent Assigned',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | GraphQL API Messages
    |--------------------------------------------------------------------------
    */
    'app' => [
        'orders' => [
            'success' => [
                'accepted' => 'Order accepted successfully',
                'rejected' => 'Order rejected successfully',
                'status_updated' => 'Order status updated successfully',
                'completed' => 'Order completed successfully',
            ],
            'errors' => [
                'unauthorized' => 'Unauthorized access',
                'order_not_found' => 'Order not found',
                'invalid_status_transition' => 'Invalid status transition',
            ],
        ],
        'reviews' => [
            'success' => [
                'created' => 'Review created successfully',
                'updated' => 'Review updated successfully',
                'deleted' => 'Review deleted successfully',
            ],
            'errors' => [
                'unauthorized' => 'Unauthorized access',
                'not_found' => 'Review not found',
                'already_exists' => 'Review already exists for this order',
                'invalid_rating' => 'Rating must be between 1 and 5',
                'cannot_update' => 'Cannot update approved review',
                'cannot_delete' => 'Cannot delete approved review',
            ],
            'status' => [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'disapproved' => 'Disapproved',
            ],
        ],
    ],

];

