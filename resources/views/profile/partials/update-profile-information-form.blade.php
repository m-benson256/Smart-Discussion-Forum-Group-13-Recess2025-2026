<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <div class="mt-6 flex items-center gap-4">
        <div class="w-16 h-16 rounded-full overflow-hidden bg-blue-600 flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
            @if ($user->avatar_path)
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
            @else
                {{ $user->initials() }}
            @endif
        </div>

        <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
            @csrf
            <label class="cursor-pointer">
                <span class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                    {{ __('Change photo') }}
                </span>
                <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
            </label>
        </form>

        @if ($user->avatar_path)
            <form method="post" action="{{ route('profile.avatar.destroy') }}">
                @csrf
                @method('delete')
                <button type="submit" class="px-3 py-2 text-sm text-red-600 hover:text-red-800">
                    {{ __('Remove') }}
                </button>
            </form>
        @endif
    </div>
    @error('avatar')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if (session('status') === 'avatar-updated')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="mt-2 text-sm text-gray-600"
        >{{ __('Photo updated.') }}</p>
    @elseif (session('status') === 'avatar-removed')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
            class="mt-2 text-sm text-gray-600"
        >{{ __('Photo removed.') }}</p>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
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

        <div class="flex items-center gap-4">
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