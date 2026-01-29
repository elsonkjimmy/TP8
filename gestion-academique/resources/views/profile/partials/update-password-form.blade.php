<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-shield-alt text-orange-500"></i>
            {{ __('Sécurité du Compte') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Assurez-vous d\'utiliser un mot de passe long et aléatoire pour rester sécurisé.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" class="text-gray-700 font-semibold" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" class="text-gray-700 font-semibold" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-2 block w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-700 font-semibold" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-lg shadow-lg hover:bg-gray-800 hover:-translate-y-0.5 transition-all duration-300">
                {{ __('Mettre à jour') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium flex items-center gap-2"
                ><i class="fas fa-check-circle"></i> {{ __('Sauvegardé.') }}</p>
            @endif
        </div>
    </form>
</section>
