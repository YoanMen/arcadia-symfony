<?php

namespace App\Service;

use App\DTO\FamousAnimalDTO;
use App\Repository\AnimalRepository;

class CouchDBManager
{
  private string $couchDBUrl;

  public function __construct(private AnimalRepository $animalRepository)
  {
    $this->couchDBUrl = $_ENV['COUCHDB_URL'];

    $this->createDatabase();
  }


  public function createAnimalDocument($animalId)
  {
    return $this->connect(method: 'POST', data: ['_id' => strval($animalId), 'click' => 1]);
  }

  public function deleteAnimalDocument($animalId): array | null
  {
    $document = $this->findAnimalDocument($animalId);

    if ($document) {
      $rev = $document['_rev'];

      return $this->connect(url: '/' . strval($animalId) . '?rev=' . $rev, method: 'DELETE');
    }

    return null;
  }

  public function addClickToAnimalDocument($animalId): array | null
  {
    $document = $this->findAnimalDocument($animalId);
    if ($document) {
      $clicks = $document['click'] + 1;
      $rev = $document['_rev'];

      return $this->connect(url: '/' . strval($animalId), method: 'PUT', data: ['click' => $clicks, '_rev' => $rev]);
    } else {
      return null;
    }
  }
  public function getFamousAnimals(): array
  {
    $data = $this->connect(url: '/_find/click_index', method: 'POST', data: [
      'selector' => ['click' => ['$gt' => 0]],
      'fields' => ["_id", "click"],
      'sort' => [['click' => 'desc']],
      'limit' => 5,
    ]);

    $animals = $data['docs'];
    $famousAnimalsDTO = [];

    foreach ($animals as $animal) {
      $famousAnimalDTO = new FamousAnimalDTO();

      $animalRepository = $this->animalRepository->findOneBy(['id' => $animal['_id']]);

      // animal exist
      if (isset($animalRepository)) {
        $name = $animalRepository->getName();

        $famousAnimalDTO->setId($animal['_id']);
        $famousAnimalDTO->setClicks($animal['click']);
        $famousAnimalDTO->setName($name);

        $famousAnimalsDTO[] = $famousAnimalDTO;
      }
    }

    return $famousAnimalsDTO;
  }
  private function createDatabase()
  {
    $database = $this->connect(method: 'PUT');

    // create index on creation of database
    if (isset($database['ok'])) {
      $this->createIndexForFamousAnimal();
    }
  }

  private function findAnimalDocument($animalId)
  {
    return $this->connect(url: '/' . strval($animalId), method: 'GET');
  }

  private function createIndexForFamousAnimal()
  {
    return $this->connect(url: '/_index', method: 'POST', data: ['index' => ['fields' => ['click']], 'name' => 'click_index']);
  }

  private function connect(string $url = '', string  $method = 'GET', array   $data  = []): array | null
  {


    $curl = curl_init($this->couchDBUrl . $url);

    curl_setopt_array($curl, [
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
      CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    $data = json_decode($response, true);
    if (isset($data['error']) && $data['error'] != "file_exists") {
      return null;
    }

    return $data;
  }
}
