<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// Import custom components from platform
import Alert from '@/Components/Alert.vue';
import AlertPopup from '@/Components/AlertPopup.vue';
import BlankSlate from '@/Components/BlankSlate.vue';
import Banner from '@/Components/Banner.vue';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import Textarea from '@/Components/Textarea.vue';
import Pagination from '@/Components/Pagination.vue';
import Loading from '@/Components/Loading.vue';
import Status from '@/Components/Status.vue';
import Statbox from '@/Components/Statbox.vue';
import Skeleton from '@/Components/Skeleton.vue';
import Progress from '@/Components/Progress.vue';
import Badge from '@/Components/Badge.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Panel from '@/Components/Panel.vue';
import Divider from '@/Components/Divider.vue';
import CustomTabs from '@/Components/Tabs.vue';
import RadioGroup from '@/Components/RadioGroup.vue';
import Slider from '@/Components/Slider.vue';
import DatePicker from '@/Components/DatePicker.vue';
import DateRangePicker from '@/Components/DateRangePicker.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputHelp from '@/Components/InputHelp.vue';
import InputSection from '@/Components/InputSection.vue';
import FormSection from '@/Components/FormSection.vue';
import SectionTitle from '@/Components/SectionTitle.vue';
import PageHeader from '@/Components/PageHeader.vue';
import StepList from '@/Components/StepList.vue';
import StepListItem from '@/Components/StepListItem.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';
import ImpersonationBanner from '@/Components/ImpersonationBanner.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import LinkPreview from '@/Components/LinkPreview.vue';
import FieldOutput from '@/Components/FieldOutput.vue';
import FieldRowOutput from '@/Components/FieldRowOutput.vue';
import ErrorList from '@/Components/ErrorList.vue';
import Fader from '@/Components/Fader.vue';
import BatchProgress from '@/Components/BatchProgress.vue';
import Sideover from '@/Components/Sideover.vue';
import SelectMenu from '@/Components/SelectMenu.vue';
import InputTag from '@/Components/InputTag.vue';
import ModelHasGenre from '@/Components/ModelHasGenre.vue';
import ToggleInput from '@/Components/ToggleInput.vue';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';

// Table components
import TableComponent from '@/Components/Tables/TableComponent.vue';
import TableHead from '@/Components/Tables/TableHead.vue';
import { Table, TableHeader, TableBody, TableRow, TableCell } from '@/Components/shadcn/ui/table';

// Tab components
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/Components/shadcn/ui/tabs';

// Dropdown components
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuTrigger,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuGroup,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuCheckboxItem
} from '@/Components/shadcn/ui/dropdown-menu';

// Icons
import { 
    MoreHorizontal, 
    Edit, 
    Trash2, 
    Download, 
    Library, 
    Video, 
    SquarePen, 
    Clock, 
    Settings, 
    Archive, 
    Share2, 
    Eye, 
    Copy,
    SortAsc,
    Filter,
    CheckCircle,
    Circle,
    XCircle,
    AlertCircle
} from 'lucide-vue-next';

// Reactive data for testing
const showAlertPopup = ref(false);
const showConfirmationModal = ref(false);
const showSideover = ref(false);
const activeTab = ref('overview');
const formData = ref({
    name: '',
    email: '',
    password: '',
    age: '',
    website: '',
    phone: '',
    message: '',
    checkbox: false,
    newsletter: false,
    switch: false,
    darkMode: false,
    select: '',
    category: '',
    radio: '',
    experience: '',
    slider: [50],
    price: [500],
    date: '',
    meeting: '',
    vacation: { start: '', end: '' },
    tags: []
});

// Dropdown reactive data
const sortBy = ref('name');
const filters = ref({
    status: ['active']
});
const currentTab = ref('shadcn');
const progressValue = ref(65);
const isLoading = ref(false);

const handleShowAlert = (type) => {
    showAlertPopup.value = true;
};

const handleShowConfirmation = () => {
    showConfirmationModal.value = true;
};

const handleShowSideover = () => {
    showSideover.value = true;
};

const simulateLoading = () => {
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
    }, 2000);
};
</script>

<template>
    <Head title="Component Showcase" />
    
    <div class="min-h-screen bg-background">
        <!-- Header -->
        <header class="border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="container flex h-14 items-center">
                <div class="mr-4 flex">
                    <ApplicationLogo class="h-8 w-auto" />
                </div>
                <div class="flex flex-1 items-center justify-between space-x-2 md:justify-end">
                    <nav class="flex items-center space-x-2">
                        <ThemeSwitcher />
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container mx-auto p-6">
            <PageHeader>
                <template #title>Component Showcase</template>
                <template #description>Testing all components copied from platform project</template>
            </PageHeader>

            <!-- Custom Components Showcase -->
            <div class="space-y-8">
                <!-- Alert Components -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Alert Components</h2>
                    
                    <Alert type="success">
                        <template #title>Success Alert</template>
                        <template #message>This is a success message using the Alert component.</template>
                    </Alert>

                    <Alert type="error">
                        <template #title>Error Alert</template>
                        <template #message>This is an error message using the Alert component.</template>
                    </Alert>

                    <Alert type="warning">
                        <template #title>Warning Alert</template>
                        <template #message>This is a warning message using the Alert component.</template>
                    </Alert>

                    <Alert type="info">
                        <template #title>Info Alert</template>
                        <template #message>This is an info message using the Alert component.</template>
                    </Alert>
                </div>

                <!-- BlankSlate Component -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">BlankSlate Component</h2>
                    
                    <div class="border rounded-lg p-8">
                        <BlankSlate>
                            <template #icon>
                                <div class="mx-auto h-12 w-12 text-gray-400">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </template>
                            <template #title>No items found</template>
                            <template #description>Get started by creating your first item.</template>
                            <template #actions>
                                <PrimaryButton>Create Item</PrimaryButton>
                            </template>
                        </BlankSlate>
                    </div>
                </div>

                <!-- Status Components -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Status Components</h2>
                    
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <Status status="active" />
                        <Status status="inactive" />
                        <Status status="pending" />
                        <Status status="error" />
                    </div>
                </div>

                <!-- Statbox Component -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Statbox Component</h2>
                    
                    <div class="grid gap-4 md:grid-cols-3">
                        <Statbox title="Total Users" value="1,234" />
                        <Statbox title="Active Users" value="987" />
                        <Statbox title="Revenue" value="$12,345" />
                    </div>
                </div>

                <!-- Loading Component -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Loading Component</h2>
                    
                    <div class="space-y-4">
                        <Loading v-if="isLoading" />
                        <PrimaryButton @click="simulateLoading">Show Loading</PrimaryButton>
                    </div>
                </div>

                <!-- Forms & Inputs -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Forms & Inputs</h2>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg border p-6 space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Contact Form</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Test all the form components</p>
                        </div>
                        
                        <!-- Text Inputs -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Text Inputs</h4>
                            
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <InputLabel for="name">Name</InputLabel>
                                    <TextInput 
                                        id="name" 
                                        name="name"
                                        v-model="formData.name" 
                                        type="text" 
                                        placeholder="Enter your name"
                                    />
                                    <InputHelp>Your full name as it appears on your ID</InputHelp>
                                </div>

                                <div>
                                    <InputLabel for="main-email">Email</InputLabel>
                                    <TextInput 
                                        id="main-email" 
                                        name="main-email"
                                        v-model="formData.email" 
                                        type="email" 
                                        placeholder="Enter your email"
                                    />
                                    <InputError>Please enter a valid email address</InputError>
                                </div>

                                <div>
                                    <InputLabel for="password">Password</InputLabel>
                                    <TextInput 
                                        id="password" 
                                        name="password"
                                        v-model="formData.password" 
                                        type="password" 
                                        placeholder="Enter your password"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="number">Age</InputLabel>
                                    <TextInput 
                                        id="number" 
                                        name="number"
                                        v-model="formData.age" 
                                        type="number" 
                                        placeholder="Enter your age"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="url">Website</InputLabel>
                                    <TextInput 
                                        id="url" 
                                        name="url"
                                        v-model="formData.website" 
                                        type="url" 
                                        placeholder="https://example.com"
                                    />
                                </div>

                                <div>
                                    <InputLabel for="tel">Phone</InputLabel>
                                    <TextInput 
                                        id="tel" 
                                        name="tel"
                                        v-model="formData.phone" 
                                        type="tel" 
                                        placeholder="+1 (555) 123-4567"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Textarea -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Textarea</h4>
                            <div>
                                <InputLabel for="message">Message</InputLabel>
                                <Textarea 
                                    id="message" 
                                    name="message"
                                    v-model="formData.message" 
                                    placeholder="Enter your message"
                                    rows="4"
                                />
                            </div>
                        </div>

                        <!-- Checkboxes -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Checkboxes</h4>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <Checkbox 
                                        id="checkbox" 
                                        :checked="formData.checkbox"
                                        @update:checked="formData.checkbox = $event"
                                    />
                                    <InputLabel for="checkbox">I agree to the terms</InputLabel>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Checkbox 
                                        id="newsletter" 
                                        :checked="formData.newsletter"
                                        @update:checked="formData.newsletter = $event"
                                    />
                                    <InputLabel for="newsletter">Subscribe to newsletter</InputLabel>
                                </div>
                            </div>
                        </div>

                        <!-- Toggle Inputs -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Toggle Inputs</h4>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-2">
                                    <ToggleInput 
                                        id="switch" 
                                        v-model="formData.switch"
                                    />
                                    <InputLabel for="switch">Enable notifications</InputLabel>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <ToggleInput 
                                        id="darkMode" 
                                        v-model="formData.darkMode"
                                    />
                                    <InputLabel for="darkMode">Dark mode</InputLabel>
                                </div>
                            </div>
                        </div>

                        <!-- Select Inputs -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Select Inputs</h4>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <InputLabel for="select">Country</InputLabel>
                                    <SelectInput 
                                        id="select" 
                                        name="select"
                                        v-model="formData.select"
                                        :items="[
                                            { id: 'us', name: 'United States' },
                                            { id: 'ca', name: 'Canada' },
                                            { id: 'uk', name: 'United Kingdom' },
                                            { id: 'au', name: 'Australia' },
                                            { id: 'de', name: 'Germany' }
                                        ]"
                                    />
                                </div>
                                <div>
                                    <InputLabel for="category">Category</InputLabel>
                                    <SelectInput 
                                        id="category" 
                                        name="category"
                                        v-model="formData.category"
                                        :items="[
                                            { id: 'tech', name: 'Technology' },
                                            { id: 'design', name: 'Design' },
                                            { id: 'marketing', name: 'Marketing' },
                                            { id: 'business', name: 'Business' }
                                        ]"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Radio Groups -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Radio Groups</h4>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <InputLabel>Gender</InputLabel>
                                    <RadioGroup 
                                        id="gender"
                                        name="gender"
                                        v-model="formData.radio"
                                        :items="[
                                            { id: 'male', name: 'Male' },
                                            { id: 'female', name: 'Female' },
                                            { id: 'other', name: 'Other' }
                                        ]"
                                    />
                                </div>
                                <div>
                                    <InputLabel>Experience Level</InputLabel>
                                    <RadioGroup 
                                        id="experience"
                                        name="experience"
                                        v-model="formData.experience"
                                        :items="[
                                            { id: 'beginner', name: 'Beginner' },
                                            { id: 'intermediate', name: 'Intermediate' },
                                            { id: 'advanced', name: 'Advanced' }
                                        ]"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Sliders -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Sliders</h4>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <InputLabel for="slider">Volume</InputLabel>
                                    <Slider 
                                        v-model="formData.slider"
                                    />
                                    <span class="text-sm text-muted-foreground">{{ formData.slider[0] }}%</span>
                                </div>
                                <div>
                                    <InputLabel for="price">Price Range</InputLabel>
                                    <Slider 
                                        v-model="formData.price"
                                    />
                                    <span class="text-sm text-muted-foreground">${{ formData.price[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date Pickers -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Date Pickers</h4>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <InputLabel for="date">Birth Date</InputLabel>
                                    <DatePicker 
                                        id="date" 
                                        v-model="formData.date"
                                    />
                                </div>
                                <div>
                                    <InputLabel for="meeting">Meeting Date</InputLabel>
                                    <DatePicker 
                                        id="meeting" 
                                        v-model="formData.meeting"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Picker -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Date Range Picker</h4>
                            <div>
                                <InputLabel>Vacation Period</InputLabel>
                                <DateRangePicker 
                                    v-model="formData.vacation"
                                />
                            </div>
                        </div>

                        <!-- Input Tag -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Input Tag</h4>
                            <div>
                                <InputLabel for="tags">Tags</InputLabel>
                                <InputTag 
                                    id="tags" 
                                    name="tags"
                                    v-model="formData.tags"
                                    placeholder="Add tags..."
                                />
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-4">
                            <h4 class="font-medium">Buttons</h4>
                            <div class="flex gap-4">
                                <PrimaryButton>Submit</PrimaryButton>
                                <SecondaryButton>Cancel</SecondaryButton>
                                <DangerButton>Delete</DangerButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback & Modals -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Feedback & Modals</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Alert Popup -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Alert Popup</h3>
                            <PrimaryButton @click="handleShowAlert('success')">Show Success Alert</PrimaryButton>
                            
                            <AlertPopup 
                                :show="showAlertPopup" 
                                type="success"
                                @close="showAlertPopup = false"
                            >
                                <template #title>Success!</template>
                                <template #description>Your action was completed successfully.</template>
                            </AlertPopup>
                        </div>

                        <!-- Confirmation Modal -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Confirmation Modal</h3>
                            <PrimaryButton @click="handleShowConfirmation">Show Confirmation</PrimaryButton>
                            
                            <ConfirmationModal 
                                :show="showConfirmationModal"
                                @close="showConfirmationModal = false"
                            >
                                <template #title>Confirm Action</template>
                                <template #description>Are you sure you want to delete this item?</template>
                            </ConfirmationModal>
                        </div>

                        <!-- Sideover -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Sideover</h3>
                            <PrimaryButton @click="handleShowSideover">Open Sideover</PrimaryButton>
                            
                            <Sideover 
                                :show="showSideover"
                                @close="showSideover = false"
                            >
                                <template #title>Sideover Title</template>
                                <template #description>This is a sideover panel</template>
                                <template #content>
                                    <p>Sideover content goes here.</p>
                                </template>
                            </Sideover>
                        </div>

                        <!-- Pagination -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Pagination</h3>
                            <Pagination 
                                :current-page="1"
                                :total-pages="10"
                                :per-page="20"
                                :total="200"
                            />
                        </div>
                    </div>
                </div>

                <!-- Tables & Data Display -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Tables & Data Display</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Table Component -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Table Component</h3>
                            <TableComponent>
                                <template #headers>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead />
                                </template>
                                <template #rows>
                                    <TableRow>
                                        <TableCell>John Doe</TableCell>
                                        <TableCell>john@example.com</TableCell>
                                        <TableCell>Admin</TableCell>
                                        <TableCell><Badge>Active</Badge></TableCell>
                                        <TableCell class="text-right">
                                            <SecondaryButton>View</SecondaryButton>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Jane Smith</TableCell>
                                        <TableCell>jane@example.com</TableCell>
                                        <TableCell>User</TableCell>
                                        <TableCell><Badge variant="secondary">Inactive</Badge></TableCell>
                                        <TableCell class="text-right">
                                            <SecondaryButton>View</SecondaryButton>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>Bob Johnson</TableCell>
                                        <TableCell>bob@example.com</TableCell>
                                        <TableCell>Editor</TableCell>
                                        <TableCell><Badge>Active</Badge></TableCell>
                                        <TableCell class="text-right">
                                            <SecondaryButton>View</SecondaryButton>
                                        </TableCell>
                                    </TableRow>
                                </template>
                            </TableComponent>
                        </div>

                        <!-- Sortable Table -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Sortable Table</h3>
                            <TableComponent>
                                <template #headers>
                                    <TableHead sortable id="id" :sortDirection="null">ID</TableHead>
                                    <TableHead sortable id="name" :sortDirection="null">Name</TableHead>
                                    <TableHead sortable id="email" :sortDirection="null">Email</TableHead>
                                    <TableHead sortable id="created_at" :sortDirection="null">Created</TableHead>
                                    <TableHead />
                                </template>
                                <template #rows>
                                    <TableRow>
                                        <TableCell>1</TableCell>
                                        <TableCell>
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium">John Doe</p>
                                            </div>
                                        </TableCell>
                                        <TableCell>john@example.com</TableCell>
                                        <TableCell>2024-01-15</TableCell>
                                        <TableCell class="text-right space-x-2">
                                            <PrimaryButton>Edit</PrimaryButton>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell>2</TableCell>
                                        <TableCell>
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium">Jane Smith</p>
                                            </div>
                                        </TableCell>
                                        <TableCell>jane@example.com</TableCell>
                                        <TableCell>2024-01-10</TableCell>
                                        <TableCell class="text-right space-x-2">
                                            <PrimaryButton>Edit</PrimaryButton>
                                        </TableCell>
                                    </TableRow>
                                </template>
                            </TableComponent>
                        </div>
                    </div>
                </div>

                <!-- Panels & Layout -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Panels & Layout</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Panel Component -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Panel Component</h3>
                            <Panel>
                                <template #title>User Information</template>
                                <template #description>Manage user details and preferences</template>
                                <template #content>
                                    <div class="space-y-4">
                                        <div>
                                            <InputLabel for="panel-name">Name</InputLabel>
                                            <TextInput id="panel-name" name="panel-name" v-model="formData.name" />
                                        </div>
                                        <div>
                                            <InputLabel for="panel-email">Email</InputLabel>
                                            <TextInput id="panel-email" name="panel-email" v-model="formData.email" type="email" />
                                        </div>
                                        <div class="flex gap-2">
                                            <PrimaryButton>Save</PrimaryButton>
                                            <SecondaryButton>Cancel</SecondaryButton>
                                        </div>
                                    </div>
                                </template>
                            </Panel>
                        </div>

                        <!-- Page Header -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Page Header</h3>
                            <PageHeader>
                                <template #title>Dashboard</template>
                                <template #description>Welcome to your dashboard</template>
                                <template #actions>
                                    <PrimaryButton>Create New</PrimaryButton>
                                </template>
                            </PageHeader>
                        </div>
                    </div>
                </div>

                <!-- Progress & Status -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Progress & Status</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Progress Bar -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Progress Bar</h3>
                            <div class="space-y-2">
                                <Progress :value="75" />
                                <span class="text-sm text-muted-foreground">75% Complete</span>
                            </div>
                        </div>

                        <!-- Step List -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Step List</h3>
                            <StepList 
                                :steps="[
                                    { step: 1, title: 'Step 1', description: 'Completed step' },
                                    { step: 2, title: 'Step 2', description: 'Current step' },
                                    { step: 3, title: 'Step 3', description: 'Upcoming step' }
                                ]"
                                :current-step="2"
                                orientation="vertical"
                            />
                        </div>
                    </div>
                </div>

                <!-- Alerts & Notifications -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Alerts & Notifications</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Alert Component -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Alert Component</h3>
                            <div class="space-y-2">
                                <Alert type="success" title="Success" description="Operation completed successfully" />
                                <Alert type="error" title="Error" description="Something went wrong" />
                                <Alert type="warning" title="Warning" description="Please review your input" />
                                <Alert type="info" title="Info" description="Here's some information" />
                            </div>
                        </div>

                        <!-- Banner -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Banner</h3>
                            <div class="space-y-2">
                                <Banner type="info" message="We'll be performing maintenance on Sunday at 2 AM." />
                                <Banner type="success" message="Your changes have been saved successfully." />
                                <Banner type="warning" message="Please review your settings before proceeding." />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Utility Components -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Utility Components</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Badge -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Badge</h3>
                            <div class="flex gap-2">
                                <Badge>Default</Badge>
                                <Badge variant="secondary">Secondary</Badge>
                                <Badge variant="destructive">Error</Badge>
                                <Badge variant="outline">Outline</Badge>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Divider</h3>
                            <div class="space-y-4">
                                <p>Content above</p>
                                <Divider />
                                <p>Content below</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dropdown Components -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Dropdown Components</h2>
                    
                    <!-- Basic Dropdown -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Basic Dropdown</h3>
                        <div class="flex gap-4">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SecondaryButton>Actions</SecondaryButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem>
                                        <Edit class="mr-2 h-4 w-4" />
                                        Edit
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        Delete
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem>
                                        <Download class="mr-2 h-4 w-4" />
                                        Export
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <!-- Dropdown with Groups -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Dropdown with Groups</h3>
                        <div class="flex gap-4">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SecondaryButton>
                                        <Library class="mr-2 h-4 w-4" />
                                        Library
                                    </SecondaryButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuGroup>
                                        <DropdownMenuLabel>Releases</DropdownMenuLabel>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem>
                                            <Library class="mr-2 h-4 w-4" />
                                            All Releases
                                        </DropdownMenuItem>
                                        <DropdownMenuItem>
                                            <SquarePen class="mr-2 h-4 w-4" />
                                            Draft Releases
                                        </DropdownMenuItem>
                                        <DropdownMenuItem>
                                            <Clock class="mr-2 h-4 w-4" />
                                            Upcoming Releases
                                        </DropdownMenuItem>
                                    </DropdownMenuGroup>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuGroup>
                                        <DropdownMenuLabel>Videos</DropdownMenuLabel>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem>
                                            <Video class="mr-2 h-4 w-4" />
                                            All Videos
                                        </DropdownMenuItem>
                                        <DropdownMenuItem>
                                            <SquarePen class="mr-2 h-4 w-4" />
                                            Draft Videos
                                        </DropdownMenuItem>
                                        <DropdownMenuItem>
                                            <Clock class="mr-2 h-4 w-4" />
                                            Upcoming Videos
                                        </DropdownMenuItem>
                                    </DropdownMenuGroup>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <!-- Dropdown with Icons -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Dropdown with Icons</h3>
                        <div class="flex gap-4">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SecondaryButton>
                                        <MoreHorizontal class="mr-2 h-4 w-4" />
                                        More Options
                                    </SecondaryButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem>
                                        <Edit class="mr-2 h-4 w-4" />
                                        Edit
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        Delete
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem>
                                        <Download class="mr-2 h-4 w-4" />
                                        Export
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <!-- Dropdown with Submenu -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Dropdown with Submenu</h3>
                        <div class="flex gap-4">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SecondaryButton>More Actions</SecondaryButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuItem>
                                        <Eye class="mr-2 h-4 w-4" />
                                        View Details
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        <Copy class="mr-2 h-4 w-4" />
                                        Duplicate
                                    </DropdownMenuItem>
                                    <DropdownMenuSub>
                                        <DropdownMenuSubTrigger>
                                            <Settings class="mr-2 h-4 w-4" />
                                            Advanced
                                        </DropdownMenuSubTrigger>
                                        <DropdownMenuSubContent>
                                            <DropdownMenuItem>
                                                <Archive class="mr-2 h-4 w-4" />
                                                Archive
                                            </DropdownMenuItem>
                                            <DropdownMenuItem>
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Move to Trash
                                            </DropdownMenuItem>
                                            <DropdownMenuItem>
                                                <Share2 class="mr-2 h-4 w-4" />
                                                Share
                                            </DropdownMenuItem>
                                        </DropdownMenuSubContent>
                                    </DropdownMenuSub>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem>
                                        <Settings class="mr-2 h-4 w-4" />
                                        Settings
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <!-- Dropdown with Radio Group -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Dropdown with Radio Group</h3>
                        <div class="flex gap-4">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SecondaryButton>
                                        <SortAsc class="mr-2 h-4 w-4" />
                                        Sort By
                                    </SecondaryButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuRadioGroup v-model="sortBy">
                                        <DropdownMenuRadioItem value="name">
                                            <Circle class="mr-2 h-4 w-4" />
                                            Name
                                        </DropdownMenuRadioItem>
                                        <DropdownMenuRadioItem value="date">
                                            <Circle class="mr-2 h-4 w-4" />
                                            Date
                                        </DropdownMenuRadioItem>
                                        <DropdownMenuRadioItem value="size">
                                            <Circle class="mr-2 h-4 w-4" />
                                            Size
                                        </DropdownMenuRadioItem>
                                    </DropdownMenuRadioGroup>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <!-- Dropdown with Checkbox Items -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Dropdown with Checkbox Items</h3>
                        <div class="flex gap-4">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SecondaryButton>
                                        <Filter class="mr-2 h-4 w-4" />
                                        Filters
                                    </SecondaryButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuCheckboxItem v-model="filters.status" value="active">
                                        <CheckCircle class="mr-2 h-4 w-4" />
                                        Active
                                    </DropdownMenuCheckboxItem>
                                    <DropdownMenuCheckboxItem v-model="filters.status" value="inactive">
                                        <XCircle class="mr-2 h-4 w-4" />
                                        Inactive
                                    </DropdownMenuCheckboxItem>
                                    <DropdownMenuCheckboxItem v-model="filters.status" value="pending">
                                        <AlertCircle class="mr-2 h-4 w-4" />
                                        Pending
                                    </DropdownMenuCheckboxItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-semibold">Tabs</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Basic Tabs -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Basic Tabs</h3>
                            <Tabs default-value="account" class="w-full">
                                <TabsList class="grid w-full grid-cols-3">
                                    <TabsTrigger value="account">Account</TabsTrigger>
                                    <TabsTrigger value="password">Password</TabsTrigger>
                                    <TabsTrigger value="settings">Settings</TabsTrigger>
                                </TabsList>
                                <TabsContent value="account" class="space-y-4">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium">Account Information</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Update your account settings and set your preferences.
                                        </p>
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel for="username">Username</InputLabel>
                                        <TextInput id="username" name="username" placeholder="Enter your username" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel for="tab-email">Email</InputLabel>
                                        <TextInput id="tab-email" name="tab-email" type="email" placeholder="Enter your email" />
                                    </div>
                                </TabsContent>
                                <TabsContent value="password" class="space-y-4">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium">Password</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Change your password here.
                                        </p>
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel for="current">Current Password</InputLabel>
                                        <TextInput id="current" name="current" type="password" />
                                    </div>
                                    <div class="space-y-2">
                                        <InputLabel for="new">New Password</InputLabel>
                                        <TextInput id="new" name="new" type="password" />
                                    </div>
                                </TabsContent>
                                <TabsContent value="settings" class="space-y-4">
                                    <div class="space-y-2">
                                        <h4 class="text-sm font-medium">Settings</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Manage your application settings.
                                        </p>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <Checkbox id="notifications" :checked="formData.checkbox" @update:checked="formData.checkbox = $event" />
                                            <InputLabel for="notifications">Enable notifications</InputLabel>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <ToggleInput id="dark-mode" v-model="formData.switch" />
                                            <InputLabel for="dark-mode">Dark mode</InputLabel>
                                        </div>
                                    </div>
                                </TabsContent>
                            </Tabs>
                        </div>

                        <!-- Custom Tabs Component -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Custom Tabs Component</h3>
                            <CustomTabs 
                                :tabs="[
                                    { id: 'overview', label: 'Overview', count: 12 },
                                    { id: 'analytics', label: 'Analytics', count: 3 },
                                    { id: 'reports', label: 'Reports', count: 7 },
                                    { id: 'notifications', label: 'Notifications', count: 2 }
                                ]"
                                v-model="activeTab"
                            />
                            <div class="mt-4 p-4 border rounded-lg">
                                <p class="text-sm text-muted-foreground">Active tab: {{ activeTab }}</p>
                                <div v-if="activeTab === 'overview'" class="mt-2">
                                    <p>Overview content goes here...</p>
                                </div>
                                <div v-else-if="activeTab === 'analytics'" class="mt-2">
                                    <p>Analytics content goes here...</p>
                                </div>
                                <div v-else-if="activeTab === 'reports'" class="mt-2">
                                    <p>Reports content goes here...</p>
                                </div>
                                <div v-else-if="activeTab === 'notifications'" class="mt-2">
                                    <p>Notifications content goes here...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
