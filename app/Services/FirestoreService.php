<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;

class FirestoreService
{
    protected $firestoreClient;

    public function __construct()
    {
        $this->firestoreClient = new FirestoreClient([
            'keyFilePath' => base_path(env('FIREBASE_CREDENTIALS')),
            'projectId' => env('FIREBASE_PROJECT_ID')
        ]);
    }

    public function getCollection($name)
    {
        return $this->firestoreClient->collection($name);
    }

    public function addDocument($collection, $data)
    {
        return $this->getCollection($collection)->add($data);
    }

    public function getAllDocuments($collection)
    {
        return $this->getCollection($collection)->documents();
    }

    public function getDocument($collection, $id)
    {
        return $this->getCollection($collection)->document($id)->snapshot();
    }

    public function updateDocument($collection, $id, $data)
    {
        return $this->getCollection($collection)->document($id)->set($data, ['merge' => true]);
    }

    public function deleteDocument($collection, $id)
    {
        return $this->getCollection($collection)->document($id)->delete();
    }

    public function getUserByEmail(string $email)
    {
        $users = $this->firestoreClient->collection('users')->where('email', '=', $email)->limit(1)->documents();
    
        foreach ($users as $user) {
            return [
                'id' => $user->id(),
                'data' => $user->data()
            ];
        }
    
        return null;
    }
}
