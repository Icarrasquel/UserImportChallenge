<?php

namespace App\Console\Commands;

use App\Models\UserImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportUsersCommand extends Command
{

    protected $signature = 'commandImportUsers';

    protected $description = 'Comando que Inserta o Actualiza Usuarios';

    public function handle()
    {
        $url = 'https://random-data-api.com/api/v2/users?size=100&is_xml=true';

        // LLama a solicitud HTTP
        $response = Http::get($url);

        // Decodifico la respuesta
        $users = $response->json();

        // Verifico que sea Existosa la respuesta
        if ($response->successful()) {

            // Declaro variables para llevar conteo de Usuarios Actualizados y Creados
            $createdUsers = 0;
            $updatedUsers = 0;

            foreach ($users as $user) {
                
                // Verifico que el usuarios exista
                $existingUser = UserImport::where('uid', $user['uid'])
                                ->where('username', $user['username'])
                                ->where('email', $user['email'])
                                ->first();
                
                if ($existingUser) {

                    $currentUser = UserImport::with(['employment', 'address', 'creditCard', 'subscription'])
                                            ->find($existingUser->uid);

                    // Se Actualiza el Usuario
                    if (
                        $currentUser->password      !== $user['password'] ||
                        $currentUser->first_name    !== $user['first_name'] ||
                        $currentUser->last_name     !== $user['last_name'] ||
                        $currentUser->avatar        !== $user['avatar'] || 
                        $currentUser->gender        !== $user['gender'] ||
                        $currentUser->phone_number  !== $user['phone_number'] ||
                        $currentUser->social_insurance_number !== $user['social_insurance_number'] ||
                        $currentUser->date_of_birth !== $user['date_of_birth'] 
                    ) {
                        $currentUser->update([
                            'password'      => $user['password'],
                            'first_name'    => $user['first_name'],
                            'last_name'     => $user['last_name'],
                            'avatar'        => $user['avatar'], 
                            'gender'        => $user['gender'],
                            'phone_number'  => $user['phone_number'],
                            'social_insurance_number' => $user['social_insurance_number'],
                            'date_of_birth' => $user['date_of_birth'],
                        ]);
                    }
                    
                    // Se Actualiza el empleo
                    if (
                        $currentUser->employment->title     !== $user['employment']['title'] ||
                        $currentUser->employment->key_skill !== $user['employment']['key_skill']
                    ) {
                        $currentUser->employment->update([
                            'title'     => $user['employment']['title'],
                            'key_skill' => $user['employment']['key_skill'],
                        ]);
                    }

                    // Se Actualiza la dirección
                    if (
                        $currentUser->address->city             !== $user['address']['city'] ||
                        $currentUser->address->street_name      !== $user['address']['street_name'] ||
                        $currentUser->address->street_address   !== $user['address']['street_address'] ||
                        $currentUser->address->zip_code         !== $user['address']['zip_code'] ||
                        $currentUser->address->state            !== $user['address']['state'] ||
                        $currentUser->address->country          !== $user['address']['country']
                    ) {
                        $currentUser->address->update([
                            'city'              => $user['address']['city'],
                            'street_name'       => $user['address']['street_name'],
                            'street_address'    => $user['address']['street_address'],
                            'zip_code'          => $user['address']['zip_code'],
                            'state'             => $user['address']['state'],
                            'country'           => $user['address']['country'],
                        ]);
                    }

                    // // Se Actualizan las coordenada
                    // if (
                    //     $currentUser->address->coordinates->lat !== $user['address']['coordinates']['lat'] ||
                    //     $currentUser->address->coordinates->lng !== $user['address']['coordinates']['lng']
                    // ) {
                    //     $currentUser->address->coordinates->update([
                    //         'lat' => $user['address']['coordinates']['lat'],
                    //         'lng' => $user['address']['coordinates']['lng'],
                    //     ]);
                    // }

                    // Se Actualizan la tarjeta de crédito
                    if ($currentUser->creditCard->cc_number !== $user['credit_card']['cc_number']) {
                        $currentUser->creditCard->update([
                            'cc_number' => $user['credit_card']['cc_number'],
                        ]);
                    }

                    // Se Actualizan la suscripción
                    if (
                        $currentUser->subscription->plan            !== $user['subscription']['plan'] ||
                        $currentUser->subscription->status          !== $user['subscription']['status'] ||
                        $currentUser->subscription->payment_method  !== $user['subscription']['payment_method'] ||
                        $currentUser->subscription->term            !== $user['subscription']['term']
                    ) {
                        $currentUser->subscription->update([
                            'plan'           => $user['subscription']['plan'],
                            'status'         => $user['subscription']['status'],
                            'payment_method' => $user['subscription']['payment_method'],
                            'term'           => $user['subscription']['term'],
                        ]);
                    }
                    $updatedUsers++;
                   
                } else {

                    // Iniciar transacción
                    DB::beginTransaction();

                    try {
                        // Se crea el usuario si no existe
                        $insertedUser = UserImport::create([
                            'uid'           => $user['uid'],
                            'password'      => $user['password'],
                            'first_name'    => $user['first_name'],
                            'last_name'     => $user['last_name'],
                            'username'      => $user['username'],
                            'email'         => $user['email'],
                            'avatar'        => $user['avatar'],
                            'gender'        => $user['gender'],
                            'phone_number'  => $user['phone_number'],
                            'social_insurance_number' => $user['social_insurance_number'],
                            'date_of_birth' => $user['date_of_birth'],
                        ]);

                        // Se insertan los datos de empleo
                        if (isset($user['employment'])) {
                            $insertedUser->employment()->create([
                                'user_imports_id'   => $insertedUser->uid,
                                'title'             => $user['employment']['title'],
                                'key_skill'         => $user['employment']['key_skill'],
                            ]);
                        }

                        // Se insertan los datos de la dirección
                        if (isset($user['address'])) {
                            $address = $insertedUser->address()->create([
                                'user_imports_id'   => $insertedUser->uid,
                                'city'              => $user['address']['city'],
                                'street_name'       => $user['address']['street_name'],
                                'street_address'    => $user['address']['street_address'],
                                'zip_code'          => $user['address']['zip_code'],
                                'state'             => $user['address']['state'],
                                'country'           => $user['address']['country'],
                            ]);

                            $addressId = $address->id;

                            // Se insertan los datos de las coordenadas
                            if (isset($user['address']['coordinates'])) {
                                $address->coordinate()->create([
                                    'address_id'    => $addressId,
                                    'lat'           => $user['address']['coordinates']['lat'],
                                    'lng'           => $user['address']['coordinates']['lng'],
                                ]);
                            }
                        }

                        // Se insertan los datos de la tarjetas de crédito
                        if (isset($user['credit_card'])) {
                            $insertedUser->creditCard()->create([
                                'user_imports_id'   => $insertedUser->uid,
                                'cc_number'         => $user['credit_card']['cc_number'],
                            ]);
                        }

                        // Se insertan los datos de la suscripción
                        if (isset($user['subscription'])) {
                            $insertedUser->subscription()->create([
                                'user_imports_id'   => $insertedUser->uid,
                                'plan'              => $user['subscription']['plan'],
                                'status'            => $user['subscription']['status'],
                                'payment_method'    => $user['subscription']['payment_method'],
                                'term'              => $user['subscription']['term'],
                            ]);
                        }
                        $createdUsers++;

                        // Confirmar transacción
                        DB::commit();
                     
                    } catch (\Exception $e) {

                        // Revertir transacción en caso de error
                        DB::rollBack();
                        return "Error al crear los usuarios.";
                    }
                }
            }

            $this->info("Se ha insertado: $createdUsers usuarios, y se han actualizado: $updatedUsers usuarios");

        } else {

            $this->info("ENDPOINT no se encuentra disponible");
        }
    }
}
