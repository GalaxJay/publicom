<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;


class Message extends BaseController
{


    protected $helpers = ['form'];

    //liste des message
    public function liste($communeId)
    {
        $messageModel = model('MessageModel');
        $communeModel = model('Commune');

        $messageListe = $messageModel->where('ID_COMMUNEMESSAGE', $communeId)->findAll();

        return view('message/listeMessages', ['messageListe' => $messageListe, 'commune' => $communeModel->find($communeId), 'isAdmin' => true]);
    }

    //page de visualisation des message
    public function visualisation($messageId)
    {
        $messageModel = model('MessageModel');
        $communeModel = model('Commune');

        $message = $messageModel->find($messageId);
        $commune = $communeModel->find($message['ID_COMMUNEMESSAGE']);
        $file = $message['FOND'];

        return view('message/visuMessage', ['message' => $message, 'commune' => $commune, 'isAdmin' => true, 'img' => $file]);
    }

    public function preSuiv($messageId,$isSuivant)
    {
        $messageModel = model('MessageModel');

        $message = $messageModel->find($messageId);;

        $correctMsg = false;
        if ($isSuivant==1){
            $i = $messageId + 1;
            $maxId=$messageModel->message_maxId();
            while ($correctMsg != true) {
                if ($i > $maxId) {
                    return redirect()->back()->with('msg', 'Il n\'y a pas de message suivant');
                } else {
                    $messageSuiv = $messageModel->find($i);
                    if ($messageSuiv != false) {
                        if ($messageSuiv['ID_COMMUNEMESSAGE'] == $message['ID_COMMUNEMESSAGE']) {
                            $correctMsg = true;
                        } else {
                            $i++;
                        }
                    } else {
                        $i++;
                    }
                }
            }

        } else {
            $i = $messageId - 1;
            while ($correctMsg != true) {
                if ($i == 0) {
                    return redirect()->back()->with('msg', 'Il n\'y a pas de message précédent');
                } else {
                    $messagePrec = $messageModel->find($i);
                    if ($messagePrec != false) {
                        if ($messagePrec['ID_COMMUNEMESSAGE'] == $message['ID_COMMUNEMESSAGE']) {
                            $correctMsg = true;
                        } else {
                            $i--;
                        }
                    } else {
                        $i--;
                    }
                }
            }

        }
        return redirect()->route('visu_message', [$i]);
    }

    //création de message
    public function ajout($communeId)
    {
        $communeModel = model('Commune');

        return view('message/ajoutMessage', ['commune' => $communeModel->find($communeId), 'isAdmin' => true]);
    }
    public function create()
    {
        $messageModel = model('MessageModel');
        $communeModel = model('Commune');

        $validationRule = [
            'fond' => [
                'label' => 'Image File',
                'rules' => [
                    'is_image[fond]',
                    'mime_in[fond,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'ext_in[fond,png,jpg,gif]'
                ],
                        'errors' => [
                    'uploaded' => 'Veuillez sélectionner une image.',
                    'is_image' => 'Le fichier doit être une image valide.',
                    'mime_in'  => 'Formats autorisés : JPG, PNG ou WEBP.',
                ],
            ],
        ];

        $isUploaded = [
            'fond' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[fond]',
                ],
            ],
        ];

        if ($this->validateData([], $isUploaded)) {
            if (! $this->validateData([], $validationRule)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            } else {
            
                $img = $this->request->getFile('fond');

                $fileName = $img->getRandomName();
                $ext = $img->getClientExtension();
                $img->move(ROOTPATH . 'public/uploads/', $fileName);
                $filepath = 'uploads/' . $fileName;

                $data = [
                    'ID_COMMUNEMESSAGE' => $this->request->getPost('idCommune'),
                    'TITRE' => $this->request->getPost('titre'),
                    'CONTENU' => $this->request->getPost('message'),
                    'POLICETITRE' => $this->request->getPost('policeTitre'),
                    'POLICECONTENU' => $this->request->getPost('policeTexte'),
                    'ALIGNEMENT' => $this->request->getPost('alignement'),
                    'FOND' => $this->request->getPost('fond'),
                    'TAILLECONTENU' => $this->request->getPost('tailleTexte'),
                    'TAILLETITRE' => $this->request->getPost('tailleTitre'),
                    'PUBLIE' => $this->request->getPost('publie'),
                    'FOND' => new File($filepath),

                ];

                if ($messageModel->insert($data)===false){
                    return redirect()->back()->withInput()->with('errors', $messageModel->errors());
                }
                return redirect()->route('liste_messages', [$this->request->getPost('idCommune')]);
            } 
       } else {
            $data = [
                'ID_COMMUNEMESSAGE' => $this->request->getPost('idCommune'),
                'TITRE' => $this->request->getPost('titre'),
                'CONTENU' => $this->request->getPost('message'),
                'POLICETITRE' => $this->request->getPost('policeTitre'),
                'POLICECONTENU' => $this->request->getPost('policeTexte'),
                'ALIGNEMENT' => $this->request->getPost('alignement'),
                'FOND' => $this->request->getPost('fond'),
                'TAILLECONTENU' => $this->request->getPost('tailleTexte'),
                'TAILLETITRE' => $this->request->getPost('tailleTitre'),

            ];

            if ($messageModel->insert($data)===false){
                    return redirect()->back()->withInput()->with('errors', $messageModel->errors());
                }
            return redirect()->route('liste_messages', [$this->request->getPost('idCommune')]);
        }
    }

    //modification des message
    public function modif($messageId)
    {
        $messageModel = model('MessageModel');
        $communeModel = model('Commune');

        $message = $messageModel->find($messageId);
        $commune = $communeModel->find($message['ID_COMMUNEMESSAGE']);

        return view('message/modifMessage', ['message' => $message, 'commune' => $commune, 'isAdmin' => true]);
    }
    public function update()
    {

        $messageModel = model('MessageModel');
        $communeModel = model('Commune');

        $messageId = $this->request->getPost('idMessage');
        $message = $messageModel->find($messageId);
        $commune = $communeModel->find($message['ID_COMMUNEMESSAGE']);


        $validationRule = [
            'fond' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[fond]',
                    'is_image[fond]',
                    'mime_in[fond,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                ],
                        'errors' => [
                    'uploaded' => 'Veuillez sélectionner une image.',
                    'is_image' => 'Le fichier doit être une image valide.',
                    'mime_in'  => 'Formats autorisés : JPG, PNG ou WEBP.',
                ],
            ],
        ];

        $isUploaded = [
            'fond' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[fond]',
                ],
            ],
        ];


        if ($this->validateData([], $isUploaded)) {
            if (! $this->validateData([], $validationRule)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }



            $img = $this->request->getFile('fond');

            if ($message['FOND'] != NULL) {
                unlink($message['FOND']);
            }

            $fileName = $img->getRandomName();
            $ext = $img->getClientExtension();
            $img->move(ROOTPATH . 'public/uploads/', $fileName);
            $filepath = 'uploads/' . $fileName;


            $data = [
                'TITRE' => $this->request->getPost('titre'),
                'CONTENU' => $this->request->getPost('message'),
                'POLICETITRE' => $this->request->getPost('policeTitre'),
                'POLICECONTENU' => $this->request->getPost('policeTexte'),
                'ALIGNEMENT' => $this->request->getPost('alignement'),
                'TAILLECONTENU' => $this->request->getPost('tailleTexte'),
                'TAILLETITRE' => $this->request->getPost('tailleTitre'),
                'FOND' => new File($filepath),
            ];


            if ($messageModel->update($this->request->getPost('idMessage'), $data)===false){
                    return redirect()->back()->withInput()->with('errors', $messageModel->errors());
                } else { return redirect()->route('liste_messages', [$this->request->getPost('idCommune')]);}
        } else {
            $data = [
                'TITRE' => $this->request->getPost('titre'),
                'CONTENU' => $this->request->getPost('message'),
                'POLICETITRE' => $this->request->getPost('policeTitre'),
                'POLICECONTENU' => $this->request->getPost('policeTexte'),
                'ALIGNEMENT' => $this->request->getPost('alignement'),
                'TAILLECONTENU' => $this->request->getPost('tailleTexte'),
                'TAILLETITRE' => $this->request->getPost('tailleTitre'),
            ];

            if ($messageModel->update($this->request->getPost('idMessage'), $data)===false){
                    return redirect()->back()->withInput()->with('errors', $messageModel->errors());    
            }
            return redirect()->route('liste_messages', [$this->request->getPost('idCommune')]);
        }
    }

    public function visuModif()
    {
        $messageModel = model('MessageModel');

        $messageModel->update($this->request->getPost('idMessage'));
        return redirect()->route('liste_messages', [$this->request->getPost('idCommune')]);
    }

    //suppresion des message
    public function delete()
    {
        $messageModel = model('MessageModel');
        $messageModel->delete($this->request->getPost('idMessage'));

        return redirect()->route('liste_messages', [$this->request->getPost('idCommune')]);
    }
}
