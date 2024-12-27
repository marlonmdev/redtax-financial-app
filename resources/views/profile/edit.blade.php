<x-app-layout>
    <section class="section">
        <div class="row">
            <div class="col-12">
                @include('profile.partials.update-profile-information-form')
                @include('profile.partials.update-password-form')
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </section>
</x-app-layout>
