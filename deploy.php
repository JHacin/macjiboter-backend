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
    ->setHostname('64.225.102.159')
    ->setRemoteUser('deployer')
    ->setDeployPath('~/macjiboter-backend');

// Hooks

after('deploy:failed', 'deploy:unlock');
