<?php

namespace Migratio\Resource\PullManager;

use Migratio\Resource\BaseManager;
use Resta\Exception\FileNotFoundException;

class Pulling extends BaseManager
{
    /**
     * @return void|mixed
     *
     * @throws FileNotFoundException
     */
    public function get()
    {
        $directory = $this->config['paths'][0];
        $dbtables = $this->schema->getConnection()->showTables();
        $migrations = $this->tableFilters();

        $list = [];

        foreach ($migrations as $table=>$item){
            $list[] = strtolower($table);
        }

        foreach ($dbtables as $dbtable){
            //if(!in_array($dbtable,$list)){

                $informations = $this->tableInformations($dbtable);

                $dbtable = ucfirst($dbtable);
                $makeDirectory = $directory.''.DIRECTORY_SEPARATOR.''.$dbtable;
                files()->makeDirectory($makeDirectory,0755,true);

                $migrationName = time().'_'.$dbtable.'';

                $content = $this->getContentFile($this->getStubPath().''.DIRECTORY_SEPARATOR.'pullCreate.stub',[
                    'className' => $dbtable,
                    'informations' => $informations
                ]);

                files()->put($makeDirectory.''.DIRECTORY_SEPARATOR.''.$migrationName.'.php',$content);
            //}
        }
    }

    /**
     * get table informations
     *
     * @param $table
     * @return string
     */
    private function tableInformations($table)
    {
        $foreignKeys = $this->schema->getConnection()->getForeignKeys($table);

        $columns = $this->schema->getConnection()->showColumnsFrom($table);

        $status = $this->schema->getConnection()->getTableStatus($table);

        $indexes = $this->schema->getConnection()->showIndexes($table);
        $multipleIndexes = $this->getMultipleIndex($indexes);

        $list = [];

        foreach ($columns as $key=>$data){

            $field      = $data['Field'];
            $list[]     = '$wizard->name(\''.$field.'\')';
            $list[]     = '->'.$this->getColumnTransformers($data['Type']).'';

            //default block
            if(!is_null($data['Default'])){
                $default = $data['Default'];
                $list[]     = '->default(\''.$default.'\')';
            }

            $getIndex = $this->getIndexInformation($indexes,$data['Field']);

            //unique block
            if($getIndex['Non_unique']=='0' && $getIndex['Key_name']!=='PRIMARY'){
                $indexName = $getIndex['Key_name'];
                $list[]     = '->unique(\''.$indexName.'\')';
            }

            //index block
            if($getIndex['Non_unique']=='1' && $getIndex['Key_name']!=='PRIMARY'){
                $columnName = $getIndex['Column_name'];
                $indexName = $getIndex['Key_name'];

                if(count($multipleIndexes[$indexName])==1){
                    $list[] = '->index(\''.$indexName.'\')';
                }
            }

            //comment block
            if(strlen($data['Comment'])>0){
                $comment = $data['Comment'];
                $list[] = '->comment(\''.$comment.'\')';
            }

            //auto increment block
            if($data['Extra']=='auto_increment'){
                $list[] = '->auto_increment()';
            }

            $list[] = ';
            ';
        }

        //table collation
        $charset = $this->schema->getConnection()->getCharsetForCollation(''.$status['Collation'].'');
        $list[] = '$wizard->table()->collation(\''.$charset.'\');
        ';

        //table indexes
        foreach ($multipleIndexes as $indexName=>$values) {
            if(count($values)>1){
                $values = '\''.implode('\',\'',$values).'\'';
                $list[] = '    $wizard->table()->indexes(\''.$indexName.'\',['.$values.']);
                ';
            }
        }

        if(count($foreignKeys)){

            $constraintName = $foreignKeys['CONSTRAINT_NAME'];
            $key = $foreignKeys['COLUMN_NAME'];
            $referenceTable = $foreignKeys['REFERENCED_TABLE_NAME'];
            $referenceColumn = $foreignKeys['REFERENCED_COLUMN_NAME'];

            if($foreignKeys['UPDATE_RULE']=='RESTRICT' && $foreignKeys['DELETE_RULE']=='RESTRICT'){
                $list[] = '    $wizard->table()->foreign()->constraint(\''.$constraintName.'\')->key(\''.$key.'\')->references(\''.$referenceTable.'\',\''.$referenceColumn.'\');
                ';
            }

            if($foreignKeys['UPDATE_RULE']!=='RESTRICT' && $foreignKeys['DELETE_RULE']=='RESTRICT'){
                $rule = $foreignKeys['UPDATE_RULE'];
                $list[] = '    $wizard->table()->foreign()->constraint(\''.$constraintName.'\')->key(\''.$key.'\')->references(\''.$referenceTable.'\',\''.$referenceColumn.'\')->onUpdate()->'.strtolower($rule).'();
                ';
            }

            if($foreignKeys['UPDATE_RULE']=='RESTRICT' && $foreignKeys['DELETE_RULE']!=='RESTRICT'){
                $rule = $foreignKeys['DELETE_RULE'];
                $list[] = '    $wizard->table()->foreign()->constraint(\''.$constraintName.'\')->key(\''.$key.'\')->references(\''.$referenceTable.'\',\''.$referenceColumn.'\')->onDelete()->'.strtolower($rule).'();
                ';
            }
        }

        return implode('',$list);
    }

    /**
     * get column transformers
     *
     * @param $column
     * @return string
     */
    private function getColumnTransformers($column)
    {
        if($column=='datetime'){
            return 'datetime()';
        }
        elseif($column=='longtext'){
            return 'longtext()';
        }
        elseif($column=='date'){
            return 'date()';
        }
        elseif(preg_match('@enum.*\((.*?)\)@',$column,$enum)){
            return 'enum(['.$enum[1].'])';
        }
        else{
            return $column;
        }
    }

    /**
     * get index information
     *
     * @param $index
     * @param $field
     * @return void|mixed
     */
    private function getIndexInformation($index,$field)
    {
        foreach ($index as $key=>$item) {

            if($item['Column_name'] == $field){
                return $index[$key];
            }
        }

        return null;
    }

    /**
     * get index information
     *
     * @param $index
     * @param $field
     * @return void|mixed
     */
    private function getMultipleIndex($index)
    {
        $list = [];
        foreach ($index as $key=>$item) {
            if($item['Non_unique']==1 && $item['Key_name']!=='PRIMARY'){
                $list[$item['Key_name']][] = $item['Column_name'];
            }
        }

        return $list;
    }
}