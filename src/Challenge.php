<?php

namespace Otto;

class Challenge
{
    protected $pdoBuilder;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.config.php';
        $this->setPdoBuilder(new PdoBuilder($config));
    }

    public function getRecords()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT
                     d.id,
                     d.first_name,
                     d.last_name,
                     b.name,
                     b.registered_address,
                     b.registration_number
                 FROM directors d
                 JOIN director_businesses db
                   ON d.id = db.director_id
                 JOIN businesses b
                 ON b.id = db.business_id';

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll($pdo::FETCH_ASSOC);

        return json_encode($rows, true);
    }
    /**
     * Use the PDOBuilder to retrieve all the director records
     *
     * @return array
     */
    public function getDirectorRecords()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT * FROM directors';

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;
    }

    /**
     * Use the PDOBuilder to retrieve a single director record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleDirectorRecord($id)
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT * FROM directors WHERE directors.id = ?';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row;
    }

    /**
     * Use the PDOBuilder to retrieve all the business records
     *
     * @return array
     */
    public function getBusinessRecords()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT * FROM businesses';

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;
    }

    /**
     * Use the PDOBuilder to retrieve a single business record with a given id
     *
     * @param int $id
     * @return array
     */
    public function getSingleBusinessRecord($id)
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT * FROM businesses WHERE businesses.id = ?';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();

        return $row;
    }

    /**
     * Use the PDOBuilder to retrieve a list of all businesses registered on a particular year
     *
     * @param int $year
     * @return array
     */
    public function getBusinessesRegisteredInYear($year)
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT * FROM businesses WHERE YEAR(businesses.registration_date) = ?';

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $year);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;
    }

    /**
     * Use the PDOBuilder to retrieve the last 100 records in the directors table
     *
     * @return array
     */
    public function getLast100Records()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT * FROM directors ORDER BY id DESC LIMIT 100';
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;
    }

    /**
     * Use the PDOBuilder to retrieve a list of all business names with the director's name in a separate column.
     * The links between directors and businesses are located inside the director_businesses table.
     *
     * Your result schema should look like this;
     *
     * | business_name | director_name |
     * ---------------------------------
     * | some_company  | some_director |
     *
     * @return array
     */
    public function getBusinessNameWithDirectorFullName()
    {
        $pdo = $this->pdoBuilder->getPdo();

        $query = 'SELECT
                      b.name as business_name,
                      CONCAT(d.first_name, \' \', d.last_name) as director_name
                  FROM businesses b
                  LEFT JOIN director_businesses db
                      ON b.id = db.business_id
                  LEFT JOIN directors d
                      ON d.id = db.director_id';

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows;
    }

    /**
     * @param PdoBuilder $pdoBuilder
     * @return $this
     */
    public function setPdoBuilder(PdoBuilder $pdoBuilder)
    {
        $this->pdoBuilder = $pdoBuilder;
        return $this;
    }

    /**
     * @return PdoBuilder
     */
    public function getPdoBuilder()
    {
        return $this->pdoBuilder;
    }
}