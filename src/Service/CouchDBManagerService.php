<?php

namespace App\Service;

use App\DTO\FamousAnimalDTO;
use App\Repository\AnimalRepository;

class CouchDBManagerService
{
    private string $couchDBUrl;

    public function __construct(private AnimalRepository $animalRepository)
    {
        $this->couchDBUrl = $_ENV['COUCHDB_URL'];

        $this->createDatabase();
    }

    /**
     * @return array<mixed>|null
     */
    public function createAnimalDocument(int $animalId): ?array
    {
        return $this->connect(method: 'POST', data: ['_id' => strval($animalId), 'click' => 1]);
    }

    /**
     * @return array<mixed>
     */
    public function deleteAnimalDocument(int $animalId): ?array
    {
        $document = $this->findAnimalDocument($animalId);

        if ($document) {
            $rev = $document['_rev'];

            return $this->connect(url: '/'.strval($animalId).'?rev='.$rev, method: 'DELETE');
        }

        return null;
    }

    /**
     * @return array<mixed>
     */
    public function addClickToAnimalDocument(int $animalId): ?array
    {
        $document = $this->findAnimalDocument($animalId);
        if ($document) {
            $clicks = $document['click'] + 1;
            $rev = $document['_rev'];

            return $this->connect(url: '/'.strval($animalId), method: 'PUT', data: ['click' => $clicks, '_rev' => $rev]);
        } else {
            return null;
        }
    }

    /**
     * @return array<mixed>
     */
    public function getFamousAnimals(): array
    {
        $data = $this->connect(url: '/_find/click_index', method: 'POST', data: [
            'selector' => ['click' => ['$gt' => 0]],
            'fields' => ['_id', 'click'],
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

    private function createDatabase(): void
    {
        $database = $this->connect(method: 'PUT');

        // create index on creation of database
        if (isset($database['ok'])) {
            $this->createIndexForFamousAnimal();
        }
    }

    /**
     * @return array<mixed>|null
     */
    private function findAnimalDocument(int $animalId): ?array
    {
        return $this->connect(url: '/'.strval($animalId), method: 'GET');
    }

    /**
     * @return array<mixed>
     */
    private function createIndexForFamousAnimal(): array
    {
        return $this->connect(url: '/_index', method: 'POST', data: ['index' => ['fields' => ['click']], 'name' => 'click_index']);
    }

    /**
     * @param array<mixed> $data
     *
     * @return array<mixed>
     */
    private function connect(string $url = '', string $method = 'GET', array $data = []): ?array
    {
        $curl = curl_init($this->couchDBUrl.$url);

        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);
        if (isset($data['error']) && 'file_exists' != $data['error']) {
            return null;
        }

        return $data;
    }
}
