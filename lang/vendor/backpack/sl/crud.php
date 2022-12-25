<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backpack Crud Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the CRUD interface.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    // Forms
    'save_action_save_and_new' => 'Shrani in dodaj nov vnos',
    'save_action_save_and_edit' => 'Shrani in ponovno uredi vnos',
    'save_action_save_and_back' => 'Shrani in pojdi nazaj',
    'save_action_save_and_preview' => 'Shrani in pojdi na predogled',
    'save_action_changed_notification' => 'Privzeto dejanje po vnosu se je spremenilo.',

    // Create form
    'add' => 'Dodaj vnos tipa',
    'back_to_all' => 'Nazaj na seznam ',
    'cancel' => 'Prekliči',
    'add_a_new' => 'Dodaj nov vnos tipa ',

    // Edit form
    'edit' => 'Uredi',
    'save' => 'Shrani',

    // Translatable models
    'edit_translations' => 'Prevod',
    'language' => 'Jezik',

    // CRUD table view
    'all' => 'Vsi ',
    'in_the_database' => 'in the database',
    'list' => 'Seznam',
    'reset' => 'Ponastavi',
    'actions' => 'Dejanja',
    'preview' => 'Predogled',
    'delete' => 'Izbriši',
    'admin' => 'Admin',
    'details_row' => 'This is the details row. Modify as you please.',
    'details_row_loading_error' => 'There was an error loading the details. Please retry.',
    'clone' => 'Kloniraj',
    'clone_success' => '<strong>Entry cloned</strong><br>A new entry has been added, with the same information as this one.',
    'clone_failure' => '<strong>Cloning failed</strong><br>The new entry could not be created. Please try again.',

    // Confirmation messages and bubbles
    'delete_confirm' => 'Ali ste prepričani, da želite izbrisati ta vnos?',
    'delete_confirmation_title' => 'Vnos izbrisan',
    'delete_confirmation_message' => 'Vnos je bil uspešno izbrisan.',
    'delete_confirmation_not_title' => 'Brisanje neuspešno',
    'delete_confirmation_not_message' => 'Prišlo je do napake. Vnos najverjetneje ni bil izbrisan.',
    'delete_confirmation_not_deleted_title' => 'Brisanje preklicano',
    'delete_confirmation_not_deleted_message' => 'Vnos ni bil izbrisan.',

    // Bulk actions
    'bulk_no_entries_selected_title' => 'No entries selected',
    'bulk_no_entries_selected_message' => 'Please select one or more items to perform a bulk action on them.',

    // Bulk delete
    'bulk_delete_are_you_sure' => 'Are you sure you want to delete these :number entries?',
    'bulk_delete_sucess_title' => 'Entries deleted',
    'bulk_delete_sucess_message' => ' items have been deleted',
    'bulk_delete_error_title' => 'Delete failed',
    'bulk_delete_error_message' => 'One or more items could not be deleted',

    // Bulk clone
    'bulk_clone_are_you_sure' => 'Are you sure you want to clone these :number entries?',
    'bulk_clone_sucess_title' => 'Entries cloned',
    'bulk_clone_sucess_message' => ' items have been cloned.',
    'bulk_clone_error_title' => 'Cloning failed',
    'bulk_clone_error_message' => 'One or more entries could not be created. Please try again.',

    // Ajax errors
    'ajax_error_title' => 'Error',
    'ajax_error_text' => 'Error loading page. Please refresh the page.',

    // DataTables translation
    'emptyTable' => 'Na voljo ni podatkov.',
    'info' => 'Prikazanih _START_ do _END_ od _TOTAL_ vnosov',
    'infoEmpty' => 'Ni vnosov',
    'infoFiltered' => '(filtrirano od _MAX_ skupnih vnosov)',
    'infoPostFix' => '.',
    'thousands' => ',',
    'lengthMenu' => '_MENU_ vnosov na stran',
    'loadingRecords' => 'Loading...',
    'processing' => 'Processing...',
    'search' => 'Išči',
    'zeroRecords' => 'Ni rezultatov.',
    'paginate' => [
        'first' => 'First',
        'last' => 'Last',
        'next' => 'Next',
        'previous' => 'Previous',
    ],
    'aria' => [
        'sortAscending' => ': activate to sort column ascending',
        'sortDescending' => ': activate to sort column descending',
    ],
    'export' => [
        'export' => 'Izvozi',
        'copy' => 'Kopiraj',
        'excel' => 'Excel',
        'csv' => 'CSV',
        'pdf' => 'PDF',
        'print' => 'Print',
        'column_visibility' => 'Vidni podatki',
    ],

    // global crud - errors
    'unauthorized_access' => 'Za dostop do te strani nimate primernih uporabniških pravic.',
    'please_fix' => 'Please fix the following errors:',

    // global crud - success / error notification bubbles
    'insert_success' => 'Vnos uspešen.',
    'update_success' => 'Urejanje uspešno.',

    // CRUD reorder view
    'reorder' => 'Reorder',
    'reorder_text' => 'Use drag&drop to reorder.',
    'reorder_success_title' => 'Done',
    'reorder_success_message' => 'Your order has been saved.',
    'reorder_error_title' => 'Error',
    'reorder_error_message' => 'Your order has not been saved.',

    // CRUD yes/no
    'yes' => 'Da',
    'no' => 'Ne',

    // CRUD filters navbar view
    'filters' => 'Filters',
    'toggle_filters' => 'Toggle filters',
    'remove_filters' => 'Počisti filtre',
    'apply' => 'Potrdi',

    //filters language strings
    'today' => 'Danes',
    'yesterday' => 'Včeraj',
    'last_7_days' => 'Zadnjih 7 dni',
    'last_30_days' => 'Zadnjih 30 dni',
    'this_month' => 'Ta mesec',
    'last_month' => 'Prejšnji mesec',
    'custom_range' => 'Prosta izbira od-do',
    'weekLabel' => 'T',

    // Fields
    'browse_uploads' => 'Browse uploads',
    'select_all' => 'Select All',
    'select_files' => 'Select files',
    'select_file' => 'Select file',
    'clear' => 'Počisti',
    'page_link' => 'Page link',
    'page_link_placeholder' => 'http://example.com/your-desired-page',
    'internal_link' => 'Internal link',
    'internal_link_placeholder' => 'Internal slug. Ex: \'admin/page\' (no quotes) for \':url\'',
    'external_link' => 'External link',
    'choose_file' => 'Izberi datoteko',
    'new_item' => 'New Item',
    'select_entry' => 'Select an entry',
    'select_entries' => 'Select entries',

    //Table field
    'table_cant_add' => 'Cannot add new :entity',
    'table_max_reached' => 'Maximum number of :max reached',

    // File manager
    'file_manager' => 'File Manager',

    // InlineCreateOperation
    'related_entry_created_success' => 'Related entry has been created and selected.',
    'related_entry_created_error' => 'Could not create related entry.',
];
