<x-guest-layout>
    <form class="text-white" method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full text-black" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" />
        <!-- Added oninput attribute to call the toggler script -->
        <x-text-input id="email" class="block mt-1 w-full text-black" type="email" name="email" :value="old('email')" required autocomplete="username" oninput="toggleRoleFields(this.value)" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- ========================================== -->
    <!-- DYNAMIC STUDENT FIELDS                    -->
    <!-- ========================================== -->
    <div id="student-fields" class="hidden mt-4 space-y-2">
        <x-input-label for="student_category" :value="__('Academic Category / Specialization')" />
        <select 
            id="student_category" 
            name="category" 
            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-black"
        >
            <option value="" disabled selected class="text-gray-400">Select your specialization...</option>
            <option value="software_engineering">Software Engineering</option>
            <option value="web_development">Web Development</option>
            <option value="programming">Programming & Core Languages</option>
            <option value="computer_science">Computer Science</option>
        </select>
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>

    <!-- ========================================== -->
    <!-- DYNAMIC LECTURER FIELDS                   -->
    <!-- ========================================== -->
    <div id="lecturer-fields" class="hidden mt-4 space-y-4">
        <!-- Degree Type Selection -->
        <div>
            <x-input-label for="degree_type" :value="__('Highest Degree Level')" />
            <select 
                id="degree_type" 
                name="degree_type" 
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-black"
            >
                <option value="" disabled selected class="text-gray-400">Select degree level...</option>
                <option value="undergraduate">Undergraduate</option>
                <option value="masters">Masters</option>
                <option value="phd">PhD</option>
            </select>
            <x-input-error :messages="$errors->get('degree_type')" class="mt-2" />
        </div>

        <!-- Desk/Office Phone Contact -->
        <div>
            <x-input-label for="desk_contact" :value="__('Desk / Office Phone Contact')" />
            <x-text-input id="desk_contact" class="block mt-1 w-full text-black" type="text" name="desk_contact" placeholder="+1 (555) 019-2834" />
            <x-input-error :messages="$errors->get('desk_contact')" class="mt-2" />
        </div>
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full text-black" type="password" name="password" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full text-black" type="password" name="password_confirmation" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <!-- Terms and Rules -->
    <div class="mt-4">
        <label for="terms" class="inline-flex items-center">
            <input 
                id="terms" 
                type="checkbox" 
                name="terms" 
                class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                required
            >
            <span class="ms-2 text-sm text-white">
                I agree to the 
                <a href="#" onclick="alert('Forum Rules:\n1. Be respectful to lecturers and peers.\n2. No plagiarism.\n3. Keep discussions academic.'); return false;" class="underline text-sm text-white hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    discussion forum rules and instructions
                </a>
            </span>
        </label>
        <x-input-error :messages="$errors->get('terms')" class="mt-2" />
    </div>

    <!-- Form Actions -->
    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-white hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <x-primary-button class="ms-4">
            {{ __('Register') }}
        </x-primary-button>
    </div>
</form>

<!-- ========================================== -->
<!-- JAVASCRIPT DOM CONTROLLER                  -->
<!-- ========================================== -->
<script>
function toggleRoleFields(emailValue) {
    const studentContainer = document.getElementById('student-fields');
    const lecturerContainer = document.getElementById('lecturer-fields');
    
    const email = emailValue.toLowerCase().trim();

    // Check domain extensions
    const isStudent = email.includes('@students.');
    const isLecturer = email.includes('@lecturers.');

    // Handle Student Elements
    if (isStudent) {
        studentContainer.classList.remove('hidden');
        document.getElementById('student_category').required = true;
    } else {
        studentContainer.classList.add('hidden');
        document.getElementById('student_category').required = false;
        document.getElementById('student_category').value = ""; 
    }

    // Handle Lecturer Elements
    if (isLecturer) {
        lecturerContainer.classList.remove('hidden');
        document.getElementById('degree_type').required = true;
        document.getElementById('desk_contact').required = true;
    } else {
        lecturerContainer.classList.add('hidden');
        document.getElementById('degree_type').required = false;
        document.getElementById('desk_contact').required = false;
        document.getElementById('degree_type').value = ""; 
        document.getElementById('desk_contact').value = ""; 
    }
}
</script>
</x-guest-layout>
