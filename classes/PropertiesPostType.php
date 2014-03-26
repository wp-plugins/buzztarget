<?php

namespace BuzzTargetLive;

class PropertiesPostType
{
    protected $text;
    
    public function __construct(Text $text)
    {
        $this->text = $text;

        add_action('init', array($this, 'initAction'));
    }
    /**
     * Runs after WordPress has finished loading but before any headers are 
     * sent.
     *
     * @access public
     *
     * @since 0.0.1
    */
    public function initAction()
    {
        register_post_type(
            // Post type
            'properties',
            // Args
            array(
                'label' => $this->text->__('PROPERTIES_POST_TYPE_LABEL'),
                'labels' => array(
                    'name' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_NAME'),
                    'singular_name' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_SINGULAR_NAME'),
                    'menu_name' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_MENU_NAME'),
                    'all_items' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_ALL_ITEMS'),
                    'add_new' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_ADD_NEW'),
                    'add_new_item' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_ADD_NEW_ITEM'),
                    'edit_item' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_EDIT_ITEM'),
                    'new_item' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_NEW_ITEM'),
                    'view_item' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_VIEW_ITEM'),
                    'search_items' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_SEARCH_ITEMS'),
                    'not_found' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_NOT_FOUND'),
                    'not_found_in_trash' => $this->text->__('PROPERTIES_POST_TYPE_LABELS_NOT_FOUND_IN_TRASH')
                ),
                'description' => $this->text->__('PROPERTIES_POST_TYPE_DESCRIPTION'),
                'supports' => array(
                    'title', 'editor',
                    'thumbnail', 'revisions', 'page-attributes'
                ),
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true, 
                'show_in_menu' => true, 
                'query_var' => true,
                'map_meta_cap' => true
            )
        );
    }
}