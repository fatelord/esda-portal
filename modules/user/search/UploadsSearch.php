<?php

namespace app\modules\user\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UploadsModel;

/**
 * UploadsSearch represents the model behind the search form about `app\modules\user\models\UserUploads`.
 */
class UploadsSearch extends UploadsModel
{
    public $owner_id;
    public $document_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UPLOAD_ID', 'USER_ID', 'PUBLICLY_AVAILABLE', 'DELETED'], 'integer'],
            [['FILE_PATH', 'COMMENTS', 'DATE_UPLOADED', 'UPDATED'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UploadsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'UPLOAD_ID' => $this->UPLOAD_ID,
            'USER_ID' => $this->owner_id, //$this->USER_ID,
            'PUBLICLY_AVAILABLE' => $this->document_type,//$this->PUBLICLY_AVAILABLE,
            'DATE_UPLOADED' => $this->DATE_UPLOADED,
            'UPDATED' => $this->UPDATED,
            'DELETED' => $this->DELETED,
        ]);

        $query->andFilterWhere(['like', 'FILE_PATH', $this->FILE_PATH])
            ->andFilterWhere(['like', 'COMMENTS', $this->COMMENTS]);

        return $dataProvider;
    }
}
