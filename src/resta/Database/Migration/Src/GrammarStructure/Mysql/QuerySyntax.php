<?php

namespace Migratio\GrammarStructure\Mysql;

class QuerySyntax extends QuerySyntaxHelper
{
    /**
     * @var array $data
     */
    protected $data = array();
    
    /**
     * @var array $syntax
     */
    protected $syntax = array();

    /**
     * @var null|string
     */
    protected $group;

    /**
     * @var array 
     */
    protected $alterExtras = array();

    /**
     * add column
     * 
     * @param $alterType
     * @return array
     */
    private function addColumn($alterType)
    {
        if(isset($alterType['place'])){


            foreach ($alterType['place'] as $placeKey=>$placeValue){
                $placeList=$placeKey .' '.$placeValue.'';
            }

            $syntax = implode("",$this->syntax);

            $alterSytanx = 'ALTER TABLE '.$this->table.' ADD COLUMN '.$syntax.' '.$placeList;

            $query=$this->schema->getConnection()->setQueryBasic($alterSytanx);

            if(count($this->alterExtras)){
                foreach($this->alterExtras as $extra){

                    $extraSyntax = 'ALTER TABLE '.$this->table.' '.$extra.'';

                    $query=$this->schema->getConnection()->setQueryBasic($extraSyntax);
                }
            }

            $this->alterExtras = [];

            return [
                'syntax'=>$syntax,
                'type'=>'addColumn',
                'result'=>$query['result'],
                'message'=>$query['message'],
            ];
        }

    }

    /**
     * change column
     * 
     * @param $alterType
     * @return array
     */
    private function change($alterType)
    {
        if(isset($alterType['place'])){

            foreach ($alterType['place'] as $placeKey=>$placeValue){
                $placeList=$placeKey .' '.$placeValue.'';
            }

            $syntax = implode("",$this->syntax);

            $columns = $this->schema->getConnection()->showColumnsFrom($this->table);

            foreach ($columns as $columnKey=>$columnData){
                if($columnData['Field']==$placeValue){
                    $changeAbleField = $columns[$columnKey+1]['Field'];
                }
            }

            $syntaxList = explode(' ',$syntax);

            if(current($syntaxList)!==$changeAbleField){
                $alterSytanx = 'ALTER TABLE '.$this->table.' change '.$changeAbleField.' '.current($syntaxList).'  '.implode(' ',array_splice($syntaxList,1)).' '.$placeList;
            }
            else{
                $alterSytanx = 'ALTER TABLE '.$this->table.' modify '.$syntax.' '.$placeList;
            }


            $query=$this->schema->getConnection()->setQueryBasic($alterSytanx);

            if(count($this->alterExtras)){
                foreach($this->alterExtras as $extra){

                    $extraSyntax = 'ALTER TABLE '.$this->table.' '.$extra.'';

                    $query=$this->schema->getConnection()->setQueryBasic($extraSyntax);
                }
            }

            $this->alterExtras = [];

            return [
                'syntax'=>$syntax,
                'type'=>'create',
                'result'=>$query['result'],
                'message'=>$query['message'],
            ];
        }
    }

    /**
     * drop column
     *
     * @param $alterType
     */
    private function dropColumn($alterType)
    {
        if(isset($this->syntax[0])){

            $column = rtrim($this->syntax[0]);

            $alterSytanx = 'alter table '.$this->table.' drop column '.$column;

            $query=$this->schema->getConnection()->setQueryBasic($alterSytanx);

            return [
                'syntax'=>$this->syntax,
                'type'=>'alter',
                'result'=>$query['result'],
                'message'=>$query['message'],
            ];


        }

    }

    /**
     * @return array
     */
    public function syntaxCreate()
    {
        $this->getWizardObjects($this->object);
        
        $existTables = $this->schema->getConnection()->showTables();

        $this->getCreateTableSyntax();

        $this->getDefaultSyntaxGroup();

        $this->syntax[]=')';

        //get table collation
        if(isset($this->data['tableCollation']['table'])){
            $this->syntax[]=' DEFAULT CHARACTER SET '.$this->data['tableCollation']['table'];
        }
        else{
            $this->syntax[]=' DEFAULT CHARACTER SET utf8';
        }

        //get engine
        if($this->data['engine']!==null)
        {
            $this->syntax[]=' ENGINE='.$this->data['engine'].' ';
        }
        else{
            $this->syntax[]=' ENGINE=InnoDB ';
        }

        $syntax = implode("",$this->syntax);

        if(in_array($this->table,$existTables)){
            return false;
        }
        else{
            $query=$this->schema->getConnection()->setQueryBasic($syntax);

            return [
                'syntax'=>$syntax,
                'type'=>'create',
                'result'=>$query['result'],
                'message'=>$query['message'],
            ];
        }
        
    }

    /**
     * @param null $group
     */
    private function getDefaultSyntaxGroup($group=null)
    {
        $this->syntax[]=implode(",",$this->getCreateDefaultList());
        
        //get unique values
        if(isset($this->data['uniqueValueList']) && count($this->data['uniqueValueList'])){
            
            if($group=='create'){
                $this->syntax[]=','.implode(',',$this->data['uniqueValueList']);
            }
            else{
                $this->alterExtras[]='ADD '.implode(',',$this->data['uniqueValueList']);
            }
            
        }

        //get index values
        if(isset($this->data['indexValueList']) && count($this->data['indexValueList'])){

            if($group=='create'){
                $this->syntax[]=','.implode(',',$this->data['indexValueList']);
            }
            else{
                $this->alterExtras[]='ADD '.implode(',',$this->data['indexValueList']); 
            }
            
        }

        //get index values for key
        if(count($this->getKeyList())){
            $this->syntax[]=','.implode(',',$this->getKeyList());
        }

        if(count($this->data['references'])){
            $this->syntax[]=$this->getReferenceSyntax($this->data['references']);
        }
    }


    /**
     * @return mixed|void
     */
    public function syntaxAlter()
    {
        $this->getWizardObjects($this->object);

        $alterType = $this->object->getAlterType();

        $group = $alterType['group'];
        
        $this->getDefaultSyntaxGroup($group);
        
        return $this->{$group}($alterType);

    }
    
}

