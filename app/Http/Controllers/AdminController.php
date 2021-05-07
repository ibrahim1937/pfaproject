<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Etudiant;
use App\Models\User;
use App\Models\Element;
use App\Models\Professeur;
use App\Models\AgentScolarite;
use App\Models\AgentExamen;
use App\Helpers\AdminHelper;
use Illuminate\Support\Arr;
use Excel;
use App\Exports\EtudiantExport;
use App\Exports\EtudiantAllExport;
use App\Imports\EtudiantAllImport;
use App\Exports\Etudiantsample;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;

class AdminController extends Controller
{
    public function module(){
        return view('admin.pages.module')->with('filieres', Filiere::all());
    }

    public function etudiant(){
        return view('admin.pages.etudiant')->with('filieres', Filiere::all());
    }
    public function professeur(){
        return view('admin.pages.professeur')->with([
            'professeurs' =>  AdminHelper::getAllProfesseur(),
            'elements' => json_decode(AdminHelper::displayAllElements(Element::all()))
        ]);
    }
    public function element(){
        return view('admin.pages.element', [
            'modules' => Module::all(),
            'professeurs' => AdminHelper::getAllProfesseur(),
            'filieres' => Filiere::all()
        ]);
    }

    public function gestionfiliere(Request $request){
        
        if($request->ajax()){
            if($request->op == 'afficher'){
                return Filiere::all();
            }
            elseif($request->op == 'ajouter'){
                $messages = [
                    'code.required' => 'le code est requis',
                    'libelle.required' => 'la libelle est requise'
                ];
                $validator = Validator::make($request->all(), [
                    'code' => 'required',
                    'libelle' => 'required',
                ], $messages);

                

                if($validator->fails()){
                    return json_encode([
                        'error' => $validator->messages()->get('*')
                    ]);
                } else {
                    $newFiliere = Filiere::create($request->only('code', 'libelle'));
                    if($newFiliere){
                        return [
                            'message' => [
                                'title' => 'success',
                                'message' => 'La filiere a était crée avec succès'
                            ],
                            'data' => Filiere::all()
                        ];
                    } else {
                        return [
                            'message' => [
                                'title' => 'fail',
                                'message' => 'Erreur lors de la connexion à la base de données'
                            ],
                            'data' => Filiere::all()
                        ];
                    }
                }
            } 
            elseif( $request->op == 'delete'){
                foreach($request->items as $item){
                    AdminHelper::deleteFiliere(intval($item));
                }
                return Filiere::all();
            } elseif( $request->op == 'update'){
                $filiere = Filiere::find($request->id);
                $filiere->code = $request->code;
                $filiere->libelle = $request->libelle;
                $filiere->save();
                return Filiere::all();
            }
        }
    }

    public function gestionModule(Request $request){
        if($request->ajax()){
            if($request->op == 'afficher'){
                return json_encode(AdminHelper::moduleOutput(Module::all()));
            }
            elseif($request->op == 'ajouter'){

                    $messages = [
                        'nom.required' => 'le nom du module est requis',
                        'id_filiere.required' => 'la filiere est requise',
                        'id_filiere.exists' => 'la filiere doit exister',

                    ];
                    $validator = Validator::make($request->all(), [
                        'nom' => 'required',
                        'id_filiere' => 'required|exists:filieres,id',
                    ], $messages);

                    // $newModule = Module::create($request->only('nom', 'id_filiere'));
                    // if($newModule){
                    //     return [
                    //         'message' => [
                    //             'title' => 'success',
                    //             'message' => 'La filiere a était crée avec succès'
                    //         ],
                    //         'data' => AdminHelper::moduleOutput(Module::all())
                    //     ];
                    // } else {
                    //     return [
                    //         'message' => [
                    //             'title' => 'fail',
                    //             'message' => 'Erreur lors de la connexion à la base de données'
                    //         ],
                    //         'data' => AdminHelper::moduleOutput(Module::all())
                    //     ];
                    // }

                    if($validator->fails()){
                        return json_encode([
                            'error' => $validator->messages()->get('*')
                        ]);
                    } else {
                        $newModule = Module::create($request->only('nom', 'id_filiere'));
                        if($newFiliere){
                            return [
                                'message' => [
                                    'title' => 'success',
                                    'message' => 'Le module a était crée avec succès'
                                ],
                                'data' => Module::all()
                            ];
                        } else {
                            return [
                                'message' => [
                                    'title' => 'fail',
                                    'message' => 'Erreur lors de la connexion à la base de données'
                                ],
                                'data' => Module::all()
                            ];
                        }
                    }
            }
             elseif( $request->op == 'delete'){
                foreach($request->items as $item){
                    Module::find($item)->delete();
                }

                return AdminHelper::moduleOutput(Module::all());
            }
            elseif( $request->op == 'update'){
                $module = Module::find($request->id)->update($request->only('nom', 'id_filiere'));
                return AdminHelper::moduleOutput(Module::all());
            }
        } 

    }


    public function gestionElement(Request $request){
        if($request->ajax()){
            if($request->op == 'afficher'){
                return AdminHelper::displayAllElements(Element::all());
            }
            elseif($request->op == 'ajouter'){
                $newElement = Element::create($request->only('nom','id_module','id_prof'));
                return AdminHelper::displayAllElements(Element::all());
            }
            elseif($request->op == 'delete'){
                foreach($request->items as $item){
                    Element::find($item)->delete();
                }
                return AdminHelper::displayAllElements(Element::all());
            }
            elseif($request->op == 'update'){
                $element = Element::find($request->id)
                ->update($request->only('nom', 'id_module', 'id_prof'));

                return AdminHelper::displayAllElements(Element::all());
            }
            elseif($request->op == 'listemodule'){
                
                return Module::where('id_filiere', $request->id_filiere)->get();
            }
            elseif($request->op == 'filitrage'){
                
                return AdminHelper::searchElements($request->filtrageOp, $request);
            }
            elseif($request->op == 'filiere'){
                $filiere = Filiere::find(Module::find($request->id_module)->id_filiere);
                return json_encode([
                       'filiere' => $filiere,
                       'modules' => Module::where('id_filiere', $filiere->id)->get()
                    ]);
            }
        }
    }

    public function gestionProfesseur(Request $request){
        if($request->ajax()){
            if($request->op == 'afficher'){
                return json_encode(AdminHelper::getAllProfesseur());
            }
            elseif($request->op == 'ajouter'){

                

                return AdminHelper::addUsersByRole($request->all(), 2, [
                    'action' => 'add'
                ]);
            }
            elseif($request->op == 'delete'){
                foreach($request->items as $item){
                    $id = Professeur::find($item)->id_user;
                    User::find($id)->delete();
                }
                return json_encode(AdminHelper::getAllProfesseur());
            }
            elseif($request->op == 'update'){
                return AdminHelper::updateUserByRole($request->all(), 2, [
                    'action' => 'update',
                    'id' => User::find(Professeur::find($request->id)->id_user)->id
                ]);
            } elseif($request->op == 'getemail'){
                return json_encode([
                    'email' => User::find(Professeur::find($request->id)->id_user)->email,
                ]);
            } elseif($request->op == 'filitrage'){

            }
            
            
        }
    }

    public function gestionAgentScolarite(Request $request){
        if($request->ajax()){
            if($request->op == 'afficher'){
                return json_encode(AdminHelper::getAllAgentScolarite());
            }
            elseif($request->op == 'ajouter'){
                return AdminHelper::addUsersByRole($request->all(), 3, [
                    'action' => 'add'
                ]);
            }
            elseif($request->op == 'delete'){
                foreach($request->items as $item){
                    $id = AgentScolarite::find($item)->id_user;
                    User::find($id)->delete();
                }
                return json_encode(AdminHelper::getAllAgentScolarite());
            }
            elseif($request->op == 'update'){
                return AdminHelper::updateUserByRole($request->all(), 3, [
                    'action' => 'update',
                    'id' => User::find(AgentScolarite::find($request->id)->id_user)->id
                ]);
            } elseif($request->op == 'getemail'){
                return json_encode([
                    'email' => User::find(AgentScolarite::find($request->id)->id_user)->email,
                ]);
            }
            
            
        }
    }

    public function gestionAgentExamen(Request $request){
        if($request->ajax()){
            if($request->op == 'afficher'){
                return json_encode(AdminHelper::getAllAgentExamen());
            }
            elseif($request->op == 'ajouter'){
                return AdminHelper::addUsersByRole($request->all(), 4, [
                    'action' => 'add'
                ]);
            }
            elseif($request->op == 'delete'){
                foreach($request->items as $item){
                    $id = AgentExamen::find($item)->id_user;
                    User::find($id)->delete();
                }
                return json_encode(AdminHelper::getAllAgentExamen());
            }
            elseif($request->op == 'update'){
                return AdminHelper::updateUserByRole($request->all(), 4, [
                    'action' => 'update',
                    'id' => User::find(AgentExamen::find($request->id)->id_user)->id
                ]);
            } elseif($request->op == 'getemail'){
                return json_encode([
                    'email' => User::find(AgentExamen::find($request->id)->id_user)->email,
                ]);
            }
            
            
        }
    }
    public function gestionEtudiant(Request $request){
        if($request->ajax()){
            if($request->op == 'afficher'){
                return json_encode(AdminHelper::getAllAgentExamen());
            }
            elseif($request->op == 'ajouter'){
                return AdminHelper::addUsersByRole($request->all(), 5, [
                    'action' => 'add'
                ]);
            }
            elseif($request->op == 'delete'){
                foreach($request->items as $item){
                    $id = AgentExamen::find($item)->id_user;
                    User::find($id)->delete();
                }
                return json_encode(AdminHelper::getAllAgentExamen());
            }
            elseif($request->op == 'update'){
                return AdminHelper::updateUserByRole($request->all(), 4, [
                    'action' => 'update',
                    'id' => User::find(AgentExamen::find($request->id)->id_user)->id
                ]);
            } elseif($request->op == 'getemail'){
                return json_encode([
                    'email' => User::find(AgentExamen::find($request->id)->id_user)->email,
                ]);
            } elseif($request->op == 'export'){
                return $this->export($request->id_filiere);
            }
            
            
        }
    }

    public function export(Request $request){

        if($request->filiere == 0){
            return Excel::download(new EtudiantAllExport(), 'etudiant.xlsx');
        }
        
        return Excel::download(new EtudiantExport($request->filiere), Filiere::find($request->filiere)->code. '-etudiant.xlsx');
    }

    public function import(Request $request){

            $rules = [
                'filiere' => 'required',
                'file' => 'required|mimes:xlsx'
            ];
            $messages = [
                'filiere.required' => 'la filiere est requise',
                'file.required' => 'le champ de fichier est obligatoire.',
                'file.mimes' => 'l\'extension du fichier doit être :xlsx'
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()){
                dd($validator->messages()->get('*'));
            } else {
                $users = Excel::toCollection(new EtudiantAllImport(), $request->file('file'));
                foreach($users[0] as $user){
                    if($user[0] == '#'){
                        dd(count($user));
                        // dd(AdminHelper::importEtudiants('all', $users));
                    } else if($user[0] == 'Filiere:'){
                        dd(count($user));
                        // $filiere = Filiere::where('code', $user[1])->get('id');
                        // dd(AdminHelper::importEtudiants('one', $users, $filiere->toArray()[0]['id']));
                    }
                }

            }

        
            $users = Excel::toCollection(new EtudiantAllImport(), $request->file('file'));
            foreach($users[0] as $user){
                if($user[0] == '#'){
                    dd(AdminHelper::importEtudiants('all', $users));
                } else if($user[0] == 'Filiere:'){
                    $filiere = Filiere::where('code', $user[1])->get('id');
                    dd(AdminHelper::importEtudiants('one', $users, $filiere->toArray()[0]['id']));
                }
            }
            return redirect()->back();
        // } catch(\Error $error){
        //     return back()->withError('test error');
        // }
        //  catch(NoTypeDetectedException $e){
        //     return back()->withError('test error');
        // }
    }

    public function exportsample(Request $request){
        return Excel::download(new Etudiantsample(), 'exemple.xlsx');
    }
}


/* 

filiere::all() => select * from 


$user = User::find(Etudiant::find(id_etudiant)->id_user);
$user->nom;
$user->email



return  [
    'nom' => $user->nom,
    'prenom' => $user->prenom
 ]
 json_encode





*/