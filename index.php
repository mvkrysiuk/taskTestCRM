<?php

include 'PipedriveAdapter.php';
include 'PipedriveEntity.php';

$pipedriveAdapter = new PipedriveAdapter('cc2b20924ed21aeeef9cdd163748ca005c52b442');

$organizationEntity = new PipedriveEntity(PipedriveEntity::ORGANIZATIONS, $pipedriveAdapter);
$organizationEntity->View($organizationEntity->getEntities(true));

$contactEntity = new PipedriveEntity(PipedriveEntity::CONTACTS, $pipedriveAdapter);
$contactEntity->View($contactEntity->getEntities(true));

$dealEntity = new PipedriveEntity(PipedriveEntity::DEALS, $pipedriveAdapter);
$dealEntity->View($dealEntity->getEntities(true));

$activities = new PipedriveEntity(PipedriveEntity::ACTIVITIES, $pipedriveAdapter);
$activities->View($activities->getEntities());
