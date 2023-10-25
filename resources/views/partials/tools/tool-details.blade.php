     {{-- Create table from this --}}
     <fieldset class="text-sm rounded-lg py-2 mt-8">
         <legend class="text-xl font-semibold">
             Details
         </legend>
         <div class="px-2 py-2">
             <div class="flex gap-2 py-2">
                 <span>Pricing Type:</span>
                 <button class="flex items-center gap-0.5 bg-gray-200/50 text-black px-2 py-0.5 rounded-full">
                     <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round"
                             d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     <span>{{ $tool->pricing_type }}</span>
                 </button>
             </div>
             <p class="py-2">
                 Home page: <a class="text-primary-600" href="{{ $tool->home_page_url }}">{{ $tool->home_page_url }}</a>
             </p>

             <div class="flex gap-2 py-2">
                 <span>Categories:</span>
                 <ul class="flex flex-wrap text-sm gap-2">
                     @foreach ($tool->categories as $category)
                         <li>
                             <a class="flex items-center gap-2 hover:text-primary-600"
                                 href="{{ route('category.show', ['category' => $category->slug]) }}">

                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                     <path stroke-linecap="round" stroke-linejoin="round"
                                         d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                 </svg>
                                 <span>{{ $category->name }}</span>
                             </a>
                         </li>
                     @endforeach
                 </ul>
             </div>


             @if (!empty($tool->instagram_id) && !empty($tool->twitter_id))
                 <div class="py-2 flex items-center gap-4">
                     <span>Social:</span>
                     <ul class="flex flex-wrap items-center justify-center gap-2">

                         @if (!empty($tool->github_id))
                             <li>
                                 <a href="https://github.com/{{ $tool->github_id }}" target="_blank" title="Github"
                                     class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                     <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                         <path fill-rule="evenodd"
                                             d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                             clip-rule="evenodd"></path>
                                     </svg>
                                 </a>
                             </li>
                         @endif

                         @if (!empty($tool->instagram_id))
                             <li>
                                 <a href="https://www.instagram.com/{{ $tool->instagram_id }}" target="_blank"
                                     title="Instagram"
                                     class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                     <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                         <path fill-rule="evenodd"
                                             d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                             clip-rule="evenodd"></path>
                                     </svg>
                                 </a>
                             </li>
                         @endif


                         @if (!empty($tool->linkedin_id))
                             <li>
                                 <a href="https://www.linkedin.com/in/{{ $tool->linkedin_id }}" target="_blank"
                                     title="LinkedIn"
                                     class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                     <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 24 24">
                                         <path
                                             d="M6.5 8C7.32843 8 8 7.32843 8 6.5C8 5.67157 7.32843 5 6.5 5C5.67157 5 5 5.67157 5 6.5C5 7.32843 5.67157 8 6.5 8Z"
                                             fill="currentColor"></path>
                                         <path
                                             d="M5 10C5 9.44772 5.44772 9 6 9H7C7.55228 9 8 9.44771 8 10V18C8 18.5523 7.55228 19 7 19H6C5.44772 19 5 18.5523 5 18V10Z"
                                             fill="currentColor"></path>
                                         <path
                                             d="M11 19H12C12.5523 19 13 18.5523 13 18V13.5C13 12 16 11 16 13V18.0004C16 18.5527 16.4477 19 17 19H18C18.5523 19 19 18.5523 19 18V12C19 10 17.5 9 15.5 9C13.5 9 13 10.5 13 10.5V10C13 9.44771 12.5523 9 12 9H11C10.4477 9 10 9.44772 10 10V18C10 18.5523 10.4477 19 11 19Z"
                                             fill="currentColor"></path>
                                         <path fill-rule="evenodd" clip-rule="evenodd"
                                             d="M20 1C21.6569 1 23 2.34315 23 4V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H20ZM20 3C20.5523 3 21 3.44772 21 4V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3H20Z"
                                             fill="currentColor"></path>
                                     </svg>
                                 </a>
                             </li>
                         @endif

                     </ul>
                 </div>
             @endif
         </div>

     </fieldset>
