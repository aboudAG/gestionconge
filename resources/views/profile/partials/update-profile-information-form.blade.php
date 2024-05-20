<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
    
        <div class="row">
            <!-- Matricule -->
            <div class="col-md-12">
                <x-input-label for="matricule" :value="__('Matricule')" />
                <x-text-input id="matricule" name="matricule" type="text" class="mt-1 block w-full" :value="$user->MATRICULE" disabled />
            </div>
        </div>
    
        <div class="row mt-3">
            <!-- Nom -->
            <div class="col-md-4">
                <x-input-label for="nom" :value="__('Nom')" />
                <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="$employe->NOM" disabled />
            </div>
    
            <!-- Prenom -->
            <div class="col-md-4">
                <x-input-label for="prenom" :value="__('Prenom')" />
                <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full" :value="$employe->PRENOM" disabled />
            </div>
    
            <!-- Role -->
            <div class="col-md-4">
                <x-input-label for="role" :value="__('Role')" />
                <x-text-input id="role" name="role" type="text" class="mt-1 block w-full" :value="$employe->role->NOM" disabled />
            </div>
        </div>
    
        <div class="row mt-3">
            <!-- Poste -->
            <div class="col-md-4">
                <x-input-label for="poste" :value="__('Poste')" />
                <x-text-input id="poste" name="poste" type="text" class="mt-1 block w-full" :value="$employe->POSTE" disabled />
            </div>
    
            <!-- StructureName -->
            <div class="col-md-4">
                <x-input-label for="structureName" :value="__('Structure')" />
                <x-text-input id="structureName" name="structureName" type="text" class="mt-1 block w-full" :value="$employe->structure->NOM" disabled />
            </div>
    
            <!-- Date d'Embauche -->
            <div class="col-md-4">
                <x-input-label for="date_embauche" :value="__('Date d\'embauche')" />
                <x-text-input id="date_embauche" name="date_embauche" type="text" class="mt-1 block w-full" :value="$employe->DATE_EMBAUCHE" disabled />
            </div>
        </div>
    
        <div class="row mt-3">
            <!-- Email -->
            <div class="col-md-12">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
    
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}
    
                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>
    
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    
        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
    
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
    
</section>
