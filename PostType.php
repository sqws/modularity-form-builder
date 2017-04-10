<?php

namespace ModularityFormBuilder;

class PostType
{
    public function __construct()
    {
        add_action('init', array($this, 'register'));
        add_action('add_meta_boxes', array($this, 'formdata'), 10, 2);
        add_action('restrict_manage_posts', array($this, 'formFilter'));

        add_action('pre_get_posts', array($this, 'queryFilter'));
    }

    public function register()
    {
        $labels = array(
            'name'                => __('Form submissions', 'modularity-form-builder'),
            'singular_name'       => __('Form submission', 'modularity-form-builder'),
            'add_new'             => _x('Add New Form submission', 'modularity-form-builder', 'modularity-form-builder'),
            'add_new_item'        => __('Add New Form submission', 'modularity-form-builder'),
            'edit_item'           => __('Edit Form submission', 'modularity-form-builder'),
            'new_item'            => __('New Form submission', 'modularity-form-builder'),
            'view_item'           => __('View Form submission', 'modularity-form-builder'),
            'search_items'        => __('Search Form submissions', 'modularity-form-builder'),
            'not_found'           => __('No Form submissions found', 'modularity-form-builder'),
            'not_found_in_trash'  => __('No Form submissions found in Trash', 'modularity-form-builder'),
            'parent_item_colon'   => __('Parent Form submission:', 'modularity-form-builder'),
            'menu_name'           => __('Form submissions', 'modularity-form-builder'),
        );

        $args = array(
            'labels'              => $labels,
            'hierarchical'        => false,
            'description'         => 'Modularity Form Builder form submissions',
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => false,
            'menu_position'       => 500,
            'menu_icon'           => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMzJweCIgaGVpZ2h0PSIyNHB4IiB2aWV3Qm94PSIwIDAgMzIgMjQiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8IS0tIEdlbmVyYXRvcjogU2tldGNoIDQzICgzODk5OSkgLSBodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2ggLS0+CiAgICA8dGl0bGU+bm91bl81OTgzNzFfY2M8L3RpdGxlPgogICAgPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CiAgICA8ZGVmcz48L2RlZnM+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0ibm91bl81OTgzNzFfY2MiIGZpbGwtcnVsZT0ibm9uemVybyIgZmlsbD0iIzAwMDAwMCI+CiAgICAgICAgICAgIDxnIGlkPSJHcm91cCI+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMjkuNSwyMCBMMTQsMjAgQzEzLjQ0OCwyMCAxMywxOS41NTMgMTMsMTkgQzEzLDE4LjQ0NyAxMy40NDgsMTggMTQsMTggTDI5LjUsMTggQzI5Ljc3NSwxOCAzMCwxNy43NzUgMzAsMTcuNSBMMzAsNi41IEMzMCw2LjIyNCAyOS43NzUsNiAyOS41LDYgTDE0LDYgQzEzLjQ0OCw2IDEzLDUuNTUyIDEzLDUgQzEzLDQuNDQ4IDEzLjQ0OCw0IDE0LDQgTDI5LjUsNCBDMzAuODc5LDQgMzIsNS4xMjIgMzIsNi41IEwzMiwxNy41IEMzMiwxOC44NzkgMzAuODc5LDIwIDI5LjUsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNiwyMCBMMi41LDIwIEMxLjEyMiwyMCAwLDE4Ljg3OSAwLDE3LjUgTDAsNi41IEMwLDUuMTIyIDEuMTIyLDQgMi41LDQgTDYsNCBDNi41NTIsNCA3LDQuNDQ4IDcsNSBDNyw1LjU1MiA2LjU1Miw2IDYsNiBMMi41LDYgQzIuMjI0LDYgMiw2LjIyNCAyLDYuNSBMMiwxNy41IEMyLDE3Ljc3NSAyLjIyNCwxOCAyLjUsMTggTDYsMTggQzYuNTUyLDE4IDcsMTguNDQ3IDcsMTkgQzcsMTkuNTUzIDYuNTUyLDIwIDYsMjAgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTAsMjQgQzkuNDQ4LDI0IDksMjMuNTUzIDksMjMgTDksMSBDOSwwLjQ0OCA5LjQ0OCwwIDEwLDAgQzEwLjU1MiwwIDExLDAuNDQ4IDExLDEgTDExLDIzIEMxMSwyMy41NTMgMTAuNTUyLDI0IDEwLDI0IFoiIGlkPSJTaGFwZSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLDIgTDcsMiBDNi40NDgsMiA2LDEuNTUyIDYsMSBDNiwwLjQ0OCA2LjQ0OCwwIDcsMCBMMTMsMCBDMTMuNTUyLDAgMTQsMC40NDggMTQsMSBDMTQsMS41NTIgMTMuNTUyLDIgMTMsMiBaIiBpZD0iU2hhcGUiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xMywyNCBMNywyNCBDNi40NDgsMjQgNiwyMy41NTMgNiwyMyBDNiwyMi40NDcgNi40NDgsMjIgNywyMiBMMTMsMjIgQzEzLjU1MiwyMiAxNCwyMi40NDcgMTQsMjMgQzE0LDIzLjU1MyAxMy41NTIsMjQgMTMsMjQgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg==',
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => false,
            'capability_type'     => 'post',
            'capabilities' => array(
                'create_posts' => 'do_not_allow',
                'delete_posts' => 'delete_posts'
            ),
            'supports'            => array('title')
        );

        register_post_type('form-submissions', $args);
    }

    public function formdata($postType, $post)
    {
        add_meta_box('formdata', 'Submission data', array($this, 'formdataDisplay'), $postType, 'normal', 'default');
    }

    public function formdataDisplay()
    {
        global $post;

        $data = array();
        $indata = get_post_meta($post->ID, 'form-data', true);
        $fields = get_fields($indata['modularity-form-id']);

        foreach ($fields['form_fields'] as $field) {
            if ($field['acf_fc_layout'] === 'sender') {
                foreach ($field['fields'] as $subfield) {
                    $data[] = array(
                        'type' => 'sender-' . $subfield,
                        'label' => $this->getTranslatedSenderField($subfield),
                        'value' => $indata[$subfield]
                    );
                }

                continue;
            }

            $data[] = array(
                'type' => $field['acf_fc_layout'],
                'label' => $field['label'],
                'value' => $indata[sanitize_title($field['label'])]
            );
        }

        include FORM_BUILDER_MODULE_PATH . 'views/admin/formdata.php';
    }

    public function formFilter()
    {
        global $typenow;
        global $wp_query;

        if ($typenow !== 'form-submissions') {
            return;
        }

        $forms = get_posts(array(
            'post_type' => 'mod-form',
            'post_status' => 'publish'
        ));

        echo '<select name="form"><option value="-1">' . __('Select form…', 'modularity-form-builder') . '</option>';

        foreach ($forms as $form) {
            $selected = isset($_GET['form']) && $_GET['form'] == $form->ID ? 'selected' : '';
            echo '<option value="' . $form->ID . '" ' . $selected . '>' . $form->post_title . '</option>';
        }
        echo '</select>';
    }

    public function queryFilter($query)
    {
        global $pagenow;
        global $typenow;

        if (!is_admin() || !$pagenow || $pagenow !== 'edit.php' || $typenow !== 'form-submissions' || !isset($_GET['form']) || !$_GET['form']) {
            return;
        }

        if (!$query->is_main_query) {
            return;
        }

        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key' => 'modularity-form-id',
                'value' => $_GET['form'],
                'compare' => '='
            )
        ));
    }

    public function getTranslatedSenderField($what)
    {
        switch ($what) {
            case 'firstname':
                return __('Firstname', 'modularity-form-builder');

            case 'lastname':
                return __('Lastname', 'modularity-form-builder');

            case 'address':
                return __('Address', 'modularity-form-builder');

            case 'phone':
                return __('Phone', 'modularity-form-builder');

            case 'email':
                return __('Email', 'modularity-form-builder');

            default:
                return $what;
        }
    }
}