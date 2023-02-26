<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:JHacin/macjiboter-backend.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('staging')
    ->set('branch', 'staging')
    ->setHostname('64.225.102.159')
    ->setRemoteUser('deployer')
    ->setDeployPath('~/macjiboter-backend');

// Tasks

task('artisan:backup:run', artisan('backup:run'))->desc('Makes a backup of the DB and storage');

// Hooks

before('artisan:migrate', 'artisan:backup:run');
after('deploy:failed', 'deploy:unlock');
