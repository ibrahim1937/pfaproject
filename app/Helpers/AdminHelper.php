<?php



namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Professeur;
use App\Models\AgentScolarite;
use App\Models\AgentExamen;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Element;
use App\Models\Etudiant;


class AdminHelper {
    
    // methode that module custume output

    public static function moduleOutput($models){
        $result = array();
        foreach ($models as $model){
            $temp = [
                'id' => $model->id,
                'nom' => $model->nom,
                'filiere' => [
                    'code' => Filiere::find($model->id_filiere)->code,
                    'libelle' => Filiere::find($model->id_filiere)->libelle,
                    'id' => $model->id_filiere
                ]
            ];
            array_push($result, $temp);
        }
        return $result;
    }

    public static function deleteFiliere($id){
        Filiere::find($id)->delete();
    }


    public static function addUsersByRole($entry, $role, $entries){

        

        // the password will be nom + prenom linked by .
        //$password = trim(strtolower($entry->nom) . '.' . strtolower($entry->prenom));

        //$newUser->password = Hash::make($password);

        // Un professeur
        

            $validatordata = AdminHelper::getValidatorParameters($entries, $role);
            $validator = Validator::make($entry, $validatordata['rules'],  $validatordata['messages']);
                if($validator->fails()){
                    return json_encode([
                        'error' => $validator->messages()->get('*'),
                    ]);
                } else {
                    $password = str_replace(' ', '',strtolower($entry['nom'])) . '.' . str_replace(' ', '', strtolower($entry['prenom']));
                    $user = User::create([
                        'nom' => $entry['nom'],
                        'prenom' => $entry['prenom'],
                        'email' => $entry['email'],
                        'cin' => $entry['cin'],
                        'password' => Hash::make($password),
                        'role_id' => $role
                    ]);
                    if($user){
                        // user created cuccessfully
                        if($role == 5){
                            $r = AdminHelper::addRefrenceToUserRole($role, [
                                'id' => $user->id,
                                'id_filiere' => $entry['id_filiere']
                            ]);
                        } else {
                            $r = AdminHelper::addRefrenceToUserRole($role, [
                                'id' => $user->id
                            ]);
                        }
                        // check if all ok 
                        if($r){
                            if($role == 2){
                                return json_encode(AdminHelper::getAllProfesseur());
                            } elseif($role == 3){
                                return json_encode(AdminHelper::getAllAgentScolarite());
                            }
                            elseif($role == 4){
                                return json_encode(AdminHelper::getAllAgentExamen());
                            } elseif($role == 5){
                                return json_encode(AdminHelper::getAllEtudiant());
                            }
                            
                        } else {
                            return json_encode(['creationerror' => 'échec lors de  l\'enregistrement des données dans la base de données']);
                        }
                        
                    } else {
                        return json_encode([
                            'creationerror' => 'échec lors de  l\'enregistrement des données dans la base de données'
                        ]);
                    }
                    
                }

    }


    public static function updateUserByRole($entry, $role, $entries){

        $validatordata = AdminHelper::getValidatorParameters($entries, $role);
        $validator = Validator::make($entry, $validatordata['rules'],  $validatordata['messages']);
                if($validator->fails()){
                    return json_encode([
                        'error' => $validator->messages()->get('*'),
                    ]);
                } else {
                    // the condition checks and we will update
                    if($role == 5){
                        // TODO student correct this
                        $user = User::find($entries['id'])->update([
                            'nom' => $entry['nom'],
                            'prenom' => $entry['prenom'],
                            'email' => $entry['email'],
                            'cin' => $entry['cin'],
                        ]);
                        if($user){
                            return json_encode([
                                'message' => 'success',
                                'data' => AdminHelper::getAllProfesseur()
                            ]);
                        } else {
                            return json_encode([
                                'updatingerror' => 'échec lors de  l\'enregistrement des données dans la base de données'
                            ]);
                        }
                    }else {
                        $user = User::find($entries['id'])->update([
                            'nom' => $entry['nom'],
                            'prenom' => $entry['prenom'],
                            'email' => $entry['email'],
                            'cin' => $entry['cin'],
                        ]);
                        if($user){
                            if($role == 2){
                                return json_encode([
                                    'message' => 'success',
                                    'data' => AdminHelper::getAllProfesseur()
                                ]);
                            }elseif($role == 3){
                                return json_encode([
                                    'message' => 'success',
                                    'data' => AdminHelper::getAllAgentScolarite()
                                ]);
                            }
                            elseif($role == 4){
                                return json_encode([
                                    'message' => 'success',
                                    'data' => AdminHelper::getAllAgentExamen()
                                ]);
                            }
                            elseif($role == 5){
                                return json_encode([
                                    'message' => 'success',
                                    'data' => AdminHelper::getAllEtudiant()
                                ]);
                            }
                        } else {
                            return json_encode([
                                'updatingerror' => 'échec lors de  l\'enregistrement des données dans la base de données'
                            ]);
                        }
                    }
                }
                    
                    
                    

                    
    }


    

    public static function displayAllElements($elements){
        $result = array();
        foreach($elements as $element){
            $p = User::find(Professeur::find($element->id_prof)->id_user);
            $temp = [
                'id' => $element->id,
                'nom' => $element->nom,
                'module' => [
                    'id' => $element->id_module,
                    'nom' => Module::find($element->id_module)->nom
                ],
                'professeur' => [
                    'id' => $element->id_prof,
                    'nom' => $p->nom . ' ' . $p->prenom
                ]
                ];

                array_push($result, $temp);
        }
        return json_encode($result);
    }

    public static function getAllProfesseur(){
        $result = array();

        foreach(Professeur::all() as $professeur){
            $u = User::find($professeur->id_user);
            $temp = [
                'id' => $professeur->id,
                'nom' => $u->nom,
                'prenom' => $u->prenom,
                'cin' => $u->cin
            ];
            array_push($result, $temp);
        }

        return json_decode(json_encode($result), FALSE);;
    }
    public static function getAllAgentScolarite(){
        $result = array();

        foreach(AgentScolarite::all() as $agent){
            $u = User::find($agent->id_user);
            $temp = [
                'id' => $agent->id,
                'nom' => $u->nom,
                'prenom' => $u->prenom,
                'cin' => $u->cin
            ];
            array_push($result, $temp);
        }

        return json_decode(json_encode($result), FALSE);
    }
    public static function getAllAgentExamen(){
        $result = array();

        foreach(AgentExamen::all() as $agent){
            $u = User::find($agent->id_user);
            $temp = [
                'id' => $agent->id,
                'nom' => $u->nom,
                'prenom' => $u->prenom,
                'cin' => $u->cin
            ];
            array_push($result, $temp);
        }

        return json_decode(json_encode($result), FALSE);
    }
    public static function getAllEtudiant(){
        $result = array();

        foreach(Etudiant::all() as $e){
            $u = User::find($e->id_user);
            $temp = [
                'id' => $e->id,
                'nom' => $u->nom,
                'prenom' => $u->prenom,
                'cin' => $u->cin,
                'filiere' => [
                    'id' => $e->id_filiere,
                    'code' => Filiere::find($e->id_filiere)->code
                ]
            ];
            array_push($result, $temp);
        }

        return json_decode(json_encode($result), FALSE);
    }

    public static function searchElements($option, $entries){
       if($option == 'module'){
            return AdminHelper::displayAllElements(Element::where('id_module', $entries->id_module)->get());
       }
       elseif($option == 'filiere'){
           $result = array();
   
                foreach(Module::where('id_filiere', $entries->id_filiere)->get() as $module){
                    $temp = json_decode(AdminHelper::displayAllElements(Element::where('id_module', $module->id)->get()));
                    foreach($temp as $e){
                        array_push($result, $e);
                    }
                    
                }
            return json_encode($result);
       }
       elseif($option == 'deux'){
            $result = array();
            $filieres = Filiere::all();
            foreach($filieres as $filiere){
                foreach(Module::where('id_filiere', $filiere->id)->where('id',$entries->id_module)->get() as $module){
                    $temp = Element::where('id_module', $module->id)->get();
                    array_push($result);
                }
            }
            return AdminHelper::displayAllElements($result);

       }
    }

    public static function getValidatorParameters($entries , $role){
        if($entries['action'] == 'add'){
            if($role == 5){
                // etudiant 
                $rules = [
                    'nom' => 'required',
                    'prenom' => 'required',
                    'email' => 'required|email|unique:users',
                    'cin' => 'required|unique:users',
                    'id_filiere' => 'required|exists:filieres,id'
                ];
                $messages = [
                    'required' => 'le champ :attribute est requis',
                    'unique' => 'le :attribute doit être unique',
                    'email.unique' => 'l\':attribute doit être unique',
                    'id_filiere.required' => 'la filiere est requise',
                ];
            
            }  else {
                $rules = [
                    'nom' => 'required',
                    'prenom' => 'required',
                    'email' => 'required|email|unique:users',
                    'cin' => 'required|unique:users',
                ];
                $messages = [
                    'required' => 'le champ :attribute est requis',
                    'unique' => 'le :attribute doit être unique',
                    'email.unique' => 'l\':attribute doit être unique',
                ];
            }
        } elseif($entries['action'] = 'update'){
            if($role == 5){
                $rules = [
                    'email' => 'required|email|unique:users,email,'.$entries['id'],
                    'cin' => 'required|unique:users,cin,'.$entries['id'],
                    'prenom' => 'required',
                    'nom'=> 'required',
                    'id_filiere' => 'required|exists:filieres,id'
                ];
                $messages = [
                    'required' => 'le champ :attribute est requis',
                    'unique' => 'le :attribute doit être unique',
                    'email.unique' => 'l\':attribute doit être unique',
                    'filiere.required' => 'la filiere est requise'
                ];
            }else {
                $rules = [
                    'email' => 'required|email|unique:users,email,'.$entries['id'],
                    'cin' => 'required|unique:users,cin,'.$entries['id'],
                    'prenom' => 'required',
                    'nom'=> 'required'
                ];
                $messages = [
                    'required' => 'le champ :attribute est requis',
                    'unique' => 'le :attribute doit être unique',
                    'email.unique' => 'l\':attribute doit être unique',
                ];
            }
        }

        return [
            'messages' => $messages,
            'rules' => $rules
        ];
        
    }

    public static function addRefrenceToUserRole($role, $entries){
        if($role == 2){
            // professeur
            $e = Professeur::create([
                'id_user' => $entries['id']
            ]); 
        }elseif($role == 3){
            $e = AgentScolarite::create([
                'id_user' => $entries['id']
            ]);
        } 
        elseif($role == 4){
            $e = AgentExamen::create([
                'id_user' => $entries['id']
            ]);
        } elseif($role == 5){
            $e = Etudiant::create([
                'id_user' => $entries['id'],
                'id_filiere' => $entries['id_filiere']
            ]);
        }

        // check if user created 

        if($e){
            return true;
        }else{
            return false;
        }
    }

    public static function importEtudiants($mode , $collections, $filiere){
        if($mode == 'one'){
            // les etudiants d'une filieres
            /*

            column 1 => numero different de id de l'etudiant => inedx 0
            column 2 => le nom dans la table users => index 1
            column 3 => le prenom dans la table users => index 2
            column 4 => le cin dans la table users => index 3
            column 5 => le email dans la table users => index 4

            starting row at index 2

            */

            $countdetails = AdminHelper::countCollection($mode, $collections);
            // if($countdetails['datacount'] == $countfiliere){
            //     return [
            //         'message' => 'true',
            //         'countfiliere' => $countfiliere,
            //         'datacount' => $countdetails['datacount']
            //     ];
            // }else {
            //     return [
            //         'error' => 'Le nombre d\'etudiant ',
            //         'countfiliere' => $countfiliere,
            //         'datacount' => $countdetails['datacount']
            //     ];
            // }

            $count = 0;
                foreach($collections[0] as $key => $row){
                    if($count >=2){
                        $entry = [
                            'nom' => $row[1],
                            'prenom' => $row[2],
                            'cin' => $row[3],
                            'email' => $row[4],
                            'id_filiere' => $filiere
                        ];
                        $entries = [
                            'action' => 'add'
                        ];
                        $add = AdminHelper::addUsersByRole($entry, 5, $entries);
                        if(property_exists((object) json_decode($add), 'error')){
                            return [
                                'erreurs' => json_decode($add)->error,
                                'ligne' => $count+1
                            ];

                        } 
                    }
                    $count += 1;

                }
                return [
                    'success' => 'success'
                ];
            }
     
        
        else if($mode == 'all'){
            // les etudiants des tous les filieres
            return AdminHelper::countCollection($mode, $collections);
        }
    }

    public static function countCollection($mode, $collections){

        if($mode == 'one'){
            foreach($collections as $key => $value){
                $count = $value ? count($value) : 0;
            }
            return [
                'lignes' => $count,
                'datacount' => $count - 2
            ];
        }
        foreach($collections as $key => $value){
            $count = $value ? count($value) : 0;
        }
        return [
            'lignes' => $count,
            'datacount' => $count - 1
        ];
        

    }

    
}