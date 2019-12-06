<?php


class PipedriveEntity
{
    CONST ORGANIZATIONS = 'organizations';
    CONST CONTACTS = 'persons';
    CONST DEALS = 'deals';
    CONST ACTIVITIES = 'activities';
    CONST NOTES = 'notes';

    protected $organizationsViewFields = [
        'id',
        'name',
        'notes_count',
        'add_time',
        'next_activity_date',
        'owner_name',
        'cc_email',
        'notes',
    ];

    protected $organizationsRelations = [
        PipedriveEntity::NOTES => [
            'check_field' => 'notes_count',
            'id_param' => 'org_id',
            'base_view_field' => 'content',
        ],
    ];

    protected $dealsRelations = [
        PipedriveEntity::NOTES => [
            'check_field' => 'notes_count',
            'id_param' => 'deal_id',
            'base_view_field' => 'content',
        ],
    ];

    protected $personsRelations = [
        PipedriveEntity::NOTES => [
            'check_field' => 'notes_count',
            'id_param' => 'person_id',
            'base_view_field' => 'content',
        ],
    ];

    protected $personsViewFields = [
        'id',
        'name',
        'notes_count',
        'add_time',
        'next_activity_date',
        'owner_name',
        'cc_email',
        'notes'
    ];

    protected $dealsViewFields = [
        'stage_id',
        'title',
        'notes_count',
        'add_time',
        'next_activity_date',
        'person_name',
        'org_name',
        'next_activity_subject',
        'formatted_weighted_value',
        'owner_name',
        'cc_email',
        'notes',
    ];

    protected $activitiesViewFields = [
        'id',
        'company_id',
        'user_id',
        'org_name',
        'person_name',
        'deal_title',
        'type',
        'add_time',
        'owner_name',
        'person_dropbox_bcc',
    ];

    protected $notesViewFields = [
        '',
    ];
    /**
     * @var PipedriveAdapter $adapter
     */
    protected $adapter = null;
    protected $entity = null;

    public function __construct($entity, PipedriveAdapter $adapter)
    {
        $this->entity = $entity;
        $this->adapter = $adapter;
    }

    public function setAdapter(PipedriveAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return array
     */
    public function getEntities($needGetRelations = false, $params = [], $start = 0, $limit = 50) {
        $data = $this->adapter->getEntityRequest($this->entity, $params, $start, $limit);

        if ($needGetRelations) {
            if (property_exists($this, $this->entity . 'Relations')) {
                if (!empty($this->{$this->entity . 'Relations'})) {
                    foreach ($this->{$this->entity . 'Relations'} as $relationEntity => $relationEntityDetails) {
                        foreach ($data as $index => $entity) {
                            if (!empty($entity[$relationEntityDetails['check_field']])) {
                                if ($res = $this->adapter->getEntityRequest($relationEntity, [
                                    $relationEntityDetails['id_param'] => $entity['id']
                                ], $start, $limit)) {
                                    $insertField = [
                                        $relationEntity => ['is_custom_related' => true],
                                    ];
                                    foreach ($res as $item) {
                                        $insertField[$relationEntity]['items'][] = $item;
                                    }

                                    $data[$index] = array_merge($data[$index], $insertField);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function View($entityData){

        echo '<table border="1">';
        echo '<tr>';
        foreach ($this->{$this->entity . 'ViewFields'} as $field) {
            echo "<th>$field</th>";;
        }
        echo '</tr>';

        foreach ($entityData as $fieldName => $entity) {
            echo "<tr>";
            foreach ($this->{$this->entity . 'ViewFields'} as $field) {
                if (!empty($entity[$field]['is_custom_related'])) {
                    echo "<td>" . $this->getMergedRowFromArray($entity[$field]['items'], $this->{$this->entity . 'Relations'}[$field]['base_view_field']) . "</td>";
                } else if (!empty($entity[$field])) {
                    echo "<td>" .  $entity[$field] . "</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
        }
        echo '</table>';
    }

    protected function getMergedRowFromArray($array, $field, $delimiter = ';')
    {
        $resultStr = '';

        foreach ($array as $item) {
            if (isset($item[$field])) {
                $resultStr .= $item[$field] . $delimiter;
            }
        }

        return $resultStr;
    }

}