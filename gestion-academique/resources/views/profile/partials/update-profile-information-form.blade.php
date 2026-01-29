<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-user-edit text-primary"></i>
            {{ __('Informations Personnelles') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Mettez à jour vos informations de profil et votre adresse email.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="first_name" :value="__('Prénom')" class="text-gray-700 font-semibold" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-2 block w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary shadow-sm" :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Nom')" class="text-gray-700 font-semibold" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-2 block w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary shadow-sm" :value="old('last_name', $user->last_name)" required autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
            <x-text-input id="email" name="email" type="email" class="mt-2 block w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary shadow-sm" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-4 bg-yellow-50 rounded-lg text-yellow-800 text-sm">
                    <p>
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}

                        <button form="send-verification" class="underline hover:text-yellow-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 font-bold">
                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-green-600">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="role" :value="__('Rôle')" class="text-gray-700 font-semibold" />
            <div class="mt-2 relative">
                <x-text-input id="role" name="role" type="text" class="block w-full bg-gray-50 text-gray-500 cursor-not-allowed rounded-lg border-gray-300 shadow-sm" :value="ucfirst(old('role', $user->role))" disabled />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
            </div>
            <p class="mt-1 text-xs text-gray-400">Le rôle ne peut pas être modifié.</p>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-lg shadow-lg hover:shadow-primary/50 hover:-translate-y-0.5 transition-all duration-300">
                {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium flex items-center gap-2"
                ><i class="fas fa-check-circle"></i> {{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>