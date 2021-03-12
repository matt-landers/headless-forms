<?php
/**
 * Plugin Name: Contact Form
 * 
 */

 add_action('graphql_register_types', function () {
  register_graphql_mutation('SubmitContactFormCF', [
    'description' => 'Simple Contact Form',
    'inputFields' => [
      'name' => [
        'type' => ['non_null' => 'string'],
        'description' => 'Name of the contact'
      ],
      'email' => [
        'type' => ['non_null' => 'string'],
        'description' => 'Email of the contact'
      ],
      'phone' => [
        'type' => 'string',
        'description' => 'Phone number of the contact'
      ]
    ],
    'outputFields' => [
        'name' => [
          'type' => 'string',
          'description' => 'Name of the contact'
        ],
        'email' => [
          'type' => 'string',
          'description' => 'Email of the contact'
        ],
        'phone' => [
          'type' => 'string',
          'description' => 'Phone number of the contact'
        ],
        'successMessage' => [
          'type' => 'string',
          'description' => 'Message returned when successful'
        ],
        'errors' => [
          'type' => ['list_of' => 'string'],
          'descriptions' => 'Validation errors from form submission'
        ],
        'query' => [
          'type' => 'RootQuery',
        ]
      ],
    'mutateAndGetPayload' => function ($input) {

      if(strpos($input['email'], '@') === false) {
        return [
          'errors' => ['Invalid email address']
        ];
      }

      $id = wp_insert_post([
        'post_type' => 'contacts',
        'post_title' => 'Form submission from: ' . $input['name'],
      ]);

      update_field('name', $input['name'], $id);
      update_field('email', $input['email'], $id);
      update_field('phone', $input['phone'], $id);

      return [
        'name' => $input['name'],
        'email' => $input['email'],
        'phone' => $input['phone'],
        'successMessage' => 'Thanks for contacting us!',
        'query' => function () {
          return true;
        }
      ];
    }
  ]);
 });