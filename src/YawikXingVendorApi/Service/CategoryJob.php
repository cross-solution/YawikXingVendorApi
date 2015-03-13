<?php
/**
 * YAWIK
 * 
 * @filesource
 * @copyright (c) 2013-2015 Cross Solution (http://cross-solution.de)
 * @license   MIT
 * @author    weitz@cross-solution.de
 */

namespace YawikXingVendorApi\Service;

/**
 * Class CategoryJob
 * @package YawikXingVendorApi\Service
 */
class CategoryJob
{
    /**
     * @var
     */
    protected $logger;

    /**
     * @var array
     */
    protected $category =
        array(
            '1'      => array('en' => 'Not categorized'),
            '2'      => array('en' => 'Administration'),
            '2.1'    => array('en' => 'Filing'),
            '2.2'    => array('en' => 'administrative support'),
            '2.3'    => array('en' => 'Order processing / data entry'),
            '2.4'    => array('en' => 'Reception'),
            '2.5'    => array('en' => 'Foreign languages / translation / interpreting'),
            '2.6'    => array('en' => 'Real estate management'),
            '2.7'    => array('en' => 'Organization / office management'),
            '2.8'    => array('en' => 'Administration'),
            '2.9'    => array('en' => 'Secretary'),
            '2.10'   => array('en' => 'Administration - Other'),
            '3'      => array('en' => 'Training / instruction'),
            '3.11'   => array('en' => 'Corporate development and training'),
            '3.12'   => array('en' => 'Adult education'),
            '3.13'   => array('en' => 'Childcare'),
            '3.14'   => array('en' => 'Education / pedagogics'),
            '3.15'   => array('en' => 'Professor'),
            '3.16'   => array('en' => 'School and university administration'),
            '3.17'   => array('en' => 'School education'),
            '3.18'   => array('en' => 'Special education'),
            '3.19'   => array('en' => 'Fitness and sports training'),
            '3.20'   => array('en' => 'Training - Other'),
            '4'      => array('en' => 'Customer service'),
            '4.21'   => array('en' => 'Flight attendant'),
            '4.22'   => array('en' => 'Hairdressing / cosmetics'),
            '4.23'   => array('en' => 'Dealer support'),
            '4.24'   => array('en' => 'Customer support'),
            '4.25'   => array('en' => 'Customer training'),
            '4.26'   => array('en' => 'Reservations and ticketing'),
            '4.27'   => array('en' => 'Counter service'),
            '4.28'   => array('en' => 'Technical customer service / hotline'),
            '4.29'   => array('en' => 'Customer service - Other'),
            '5'      => array('en' => 'Design and architecture'),
            '5.30'   => array('en' => 'Computer animation and multimedia'),
            '5.31'   => array('en' => 'Creative direction'),
            '5.32'   => array('en' => 'Graphics / illustration'),
            '5.33'   => array('en' => 'Industrial design'),
            '5.34'   => array('en' => 'Fashion and jewelry design'),
            '5.35'   => array('en' => 'Video and photography'),
            '5.36'   => array('en' => 'User interface design / user experience / Web design'),
            '5.37'   => array('en' => 'Design and architecture - Other'),
            '6'      => array('en' => 'Purchasing, transport and logistics'),
            '6.38'   => array('en' => 'Purchasing'),
            '6.39'   => array('en' => 'Dangerous goods management'),
            '6.40'   => array('en' => 'Import / export administration'),
            '6.41'   => array('en' => 'Cost estimating'),
            '6.42'   => array('en' => 'Food logistics'),
            '6.43'   => array('en' => 'Transport management'),
            '6.44'   => array('en' => 'Supplier support'),
            '6.45'   => array('en' => 'Postal and delivery services / courier'),
            '6.46'   => array('en' => 'Haulage'),
            '6.47'   => array('en' => 'Road, rail and air freight'),
            '6.48'   => array('en' => 'Supply chain management'),
            '6.49'   => array('en' => 'Packaging and shipping'),
            '6.50'   => array('en' => 'Warehousing, storage and goods logistics'),
            '6.51'   => array('en' => 'Materials, raw material logistics'),
            '6.52'   => array('en' => 'Purchasing, transport and logistics - Other'),
            '7'      => array('en' => 'Management / executive and strategic management'),
            '7.53'   => array('en' => 'Consulting / management consulting'),
            '7.54'   => array('en' => 'Company and department management'),
            '7.55'   => array('en' => 'Business development'),
            '7.56'   => array('en' => 'Management'),
            '7.57'   => array('en' => 'Board of directors / management'),
            '7.58'   => array('en' => 'Franchising'),
            '7.59'   => array('en' => 'Mergers'),
            '7.60'   => array('en' => 'Strategy and planning'),
            '7.61'   => array('en' => 'Management / executive - Other'),
            '8'      => array('en' => 'Production, construction and trade'),
            '8.62'   => array('en' => 'Construction site supervision'),
            '8.63'   => array('en' => 'Electrics, plumbing, heating and air conditioning'),
            '8.64'   => array('en' => 'Industrial production'),
            '8.65'   => array('en' => 'Site supervisor'),
            '8.66'   => array('en' => 'Painter / varnisher'),
            '8.67'   => array('en' => 'Mechanic'),
            '8.68'   => array('en' => 'Metalworking / blacksmith'),
            '8.69'   => array('en' => 'Jewelry'),
            '8.70'   => array('en' => 'Carpenter and joiner'),
            '8.71'   => array('en' => 'Technical drawing / CAD'),
            '8.72'   => array('en' => 'Surveying'),
            '8.73'   => array('en' => 'Plasterer'),
            '8.74'   => array('en' => 'Production, construction and trade - Other'),
            '9'      => array('en' => 'Hotel and catering'),
            '9.75'   => array('en' => 'Cleaning and housekeeping'),
            '9.76'   => array('en' => 'Tourism and travel'),
            '9.77'   => array('en' => 'Catering'),
            '9.78'   => array('en' => 'Hotel and catering - Other'),
            '10'     => array('en' => 'Engineering / technical'),
            '10.79'  => array('en' => 'Civil engineer'),
            '10.80'  => array('en' => 'Stage, audio and video engineer'),
            '10.81'  => array('en' => 'CAD construction and visualization'),
            '10.82'  => array('en' => 'Chemical technology'),
            '10.83'  => array('en' => 'Electrical engineer, electrical technician'),
            '10.84'  => array('en' => 'Power engineering and nuclear technology'),
            '10.85'  => array('en' => 'Radio technology'),
            '10.86'  => array('en' => 'Aeronautics and astronautics'),
            '10.87'  => array('en' => 'Mechanical engineering / vehicle maintenance'),
            '10.88'  => array('en' => 'Materials science'),
            '10.89'  => array('en' => 'Mechatronics engineer'),
            '10.90'  => array('en' => 'Process control'),
            '10.91'  => array('en' => 'Process technology'),
            '10.92'  => array('en' => 'Shipbuilding'),
            '10.93'  => array('en' => 'Environmental technology'),
            '10.94'  => array('en' => 'Process technology engineer'),
            '10.95'  => array('en' => 'Sales engineer'),
            '10.96'  => array('en' => 'Engineering / technical - Other'),
            '11'     => array('en' => 'IT and telecommunication'),
            '11.97'  => array('en' => 'Application analysis'),
            '11.98'  => array('en' => 'Application support and maintenance'),
            '11.99'  => array('en' => 'Data warehousing'),
            '11.100' => array('en' => 'Database development and administration'),
            '11.101' => array('en' => 'DTP and graphic design'),
            '11.102' => array('en' => 'e-business and e-commerce'),
            '11.103' => array('en' => 'Embedded systems and firmware development'),
            '11.104' => array('en' => 'Hardware development / engineering'),
            '11.105' => array('en' => 'Help desk and technical support'),
            '11.106' => array('en' => 'Computer science'),
            '11.107' => array('en' => 'IT architecture'),
            '11.108' => array('en' => 'IT consulting'),
            '11.109' => array('en' => 'IT product management'),
            '11.110' => array('en' => 'IT project management'),
            '11.111' => array('en' => 'IT quality assurance and testing'),
            '11.112' => array('en' => 'IT systems analysis'),
            '11.113' => array('en' => 'IT training'),
            '11.114' => array('en' => 'IT management'),
            '11.115' => array('en' => 'System / network set-up, administration, maintenance and security'),
            '11.116' => array('en' => 'ERP / SAP consulting and implementation'),
            '11.117' => array('en' => 'SAP development'),
            '11.118' => array('en' => 'Software and system architecture'),
            '11.119' => array('en' => 'Software consulting'),
            '11.120' => array('en' => 'Software and Web development'),
            '11.121' => array('en' => 'Systems administration'),
            '11.122' => array('en' => 'Technical writing'),
            '11.123' => array('en' => 'Telecommunication and mobile systems'),
            '11.124' => array('en' => 'Web and user interface design'),
            '11.125' => array('en' => 'Webmaster'),
            '11.126' => array('en' => 'IT and telecommunication - Other'),
            '12'     => array('en' => 'Maintenance'),
            '12.127' => array('en' => 'Electrical systems / mechatronics'),
            '12.128' => array('en' => 'Facility management'),
            '12.129' => array('en' => 'Facility management'),
            '12.130' => array('en' => 'Heating / air conditioning'),
            '12.131' => array('en' => 'Mechanics / automotive electronics'),
            '12.132' => array('en' => 'Plumbing'),
            '12.133' => array('en' => 'Landscaping'),
            '12.134' => array('en' => 'Machine assembly / maintenance'),
            '12.135' => array('en' => 'Metalworking shop'),
            '12.136' => array('en' => 'Maintenance - Other'),
            '13'     => array('en' => 'Marketing and advertising'),
            '13.137' => array('en' => 'Brand marketing'),
            '13.138' => array('en' => 'Event marketing / promotion'),
            '13.139' => array('en' => 'PR, investor relations'),
            '13.140' => array('en' => 'Marketing manager'),
            '13.141' => array('en' => 'Marketing assistant'),
            '13.142' => array('en' => 'Market research and analysis'),
            '13.143' => array('en' => 'Media planning'),
            '13.144' => array('en' => 'Online marketing'),
            '13.145' => array('en' => 'Product management'),
            '13.146' => array('en' => 'Telemarketing'),
            '13.147' => array('en' => 'Advertising and communication'),
            '13.148' => array('en' => 'Strategic marketing'),
            '13.149' => array('en' => 'Marketing and advertising - Other'),
            '14'     => array('en' => 'Health, medical and social'),
            '14.150' => array('en' => 'Doctor / physician'),
            '14.151' => array('en' => 'Medical assistant (PTA, MTA, etc.)'),
            '14.152' => array('en' => 'Nutrition consulting'),
            '14.153' => array('en' => 'Youth and social worker'),
            '14.154' => array('en' => 'Optician and acoustician'),
            '14.155' => array('en' => 'Nursing sector'),
            '14.156' => array('en' => 'Pharmaceutical sales representative'),
            '14.157' => array('en' => 'Pharmacy'),
            '14.158' => array('en' => 'Doctors office employee'),
            '14.159' => array('en' => 'Medical and outpatient services'),
            '14.160' => array('en' => 'Sport and fitness'),
            '14.161' => array('en' => 'Therapeutic professions'),
            '14.162' => array('en' => 'Health, medicine, social - Other'),
            '15'     => array('en' => 'Research, development and science'),
            '15.163' => array('en' => 'Chemistry, physics and biology'),
            '15.164' => array('en' => 'Mathematics, statistics and computer science'),
            '15.165' => array('en' => 'Medical research'),
            '15.166' => array('en' => 'New products - Research and development'),
            '15.167' => array('en' => 'Pharmaceutical research'),
            '15.168' => array('en' => 'Environment and geology'),
            '15.169' => array('en' => 'Research, development and science - Other'),
            '16'     => array('en' => 'Human resources'),
            '16.170' => array('en' => 'HR administration'),
            '16.171' => array('en' => 'HR consulting'),
            '16.172' => array('en' => 'Recruiting and HR marketing'),
            '16.173' => array('en' => 'HR development and training'),
            '16.174' => array('en' => 'HR management'),
            '16.175' => array('en' => 'HR department'),
            '16.176' => array('en' => 'Human resources - Other'),
            '17'     => array('en' => 'Art and culture'),
            '17.177' => array('en' => 'Photography'),
            '17.178' => array('en' => 'History and archeology'),
            '17.179' => array('en' => 'Artist'),
            '17.180' => array('en' => 'Museums and galleries'),
            '17.181' => array('en' => 'Theater, drama, music and dance'),
            '17.182' => array('en' => 'Art and culture - Other'),
            '18'     => array('en' => 'Production'),
            '18.183' => array('en' => 'Production management'),
            '18.184' => array('en' => 'Operations technology'),
            '18.185' => array('en' => 'Printing company'),
            '18.186' => array('en' => 'Production assembly'),
            '18.187' => array('en' => 'Production technology and process engineering'),
            '18.188' => array('en' => 'Dangerous goods management'),
            '18.189' => array('en' => 'Casting and moulding'),
            '18.190' => array('en' => 'Metalworking and welding technology'),
            '18.191' => array('en' => 'Sewing and tailoring'),
            '18.192' => array('en' => 'Food production'),
            '18.193' => array('en' => 'Production planning'),
            '18.194' => array('en' => 'Telecommunications technology'),
            '18.195' => array('en' => 'Machining'),
            '18.196' => array('en' => 'Production - Other'),
            '19'     => array('en' => 'Project management'),
            '19.197' => array('en' => 'IT project management'),
            '19.198' => array('en' => 'Program management'),
            '19.199' => array('en' => 'Project management'),
            '19.200' => array('en' => 'Project management - Other'),
            '20'     => array('en' => 'Quality control'),
            '20.201' => array('en' => 'Construction site supervision'),
            '20.202' => array('en' => 'ISO certification'),
            '20.203' => array('en' => 'Quality control'),
            '20.204' => array('en' => 'Six Sigma / TQM / Black Belt'),
            '20.205' => array('en' => 'Vehicle inspection'),
            '20.206' => array('en' => 'Environmental protection'),
            '20.207' => array('en' => 'Software quality assurance'),
            '20.208' => array('en' => 'Quality control - Other'),
            '21'     => array('en' => 'Banking, insurance and financial services'),
            '21.209' => array('en' => 'Investment consulting'),
            '21.210' => array('en' => 'Asset and fund management'),
            '21.211' => array('en' => 'Compliance and security'),
            '21.212' => array('en' => 'Corporate banking'),
            '21.213' => array('en' => 'Estate agent'),
            '21.214' => array('en' => 'Investment banking'),
            '21.215' => array('en' => 'Credit analysis'),
            '21.216' => array('en' => 'Mergers'),
            '21.217' => array('en' => 'Private banking'),
            '21.218' => array('en' => 'Counter service'),
            '21.219' => array('en' => 'Insurance: Administration'),
            '21.220' => array('en' => 'Broker'),
            '21.221' => array('en' => 'Securities trading'),
            '21.222' => array('en' => 'Payments and transactions'),
            '21.223' => array('en' => 'Banking, insurance and financial services - Other'),
            '22'     => array('en' => 'Finance and accounting'),
            '22.224' => array('en' => 'Financial accounting'),
            '22.225' => array('en' => 'Business analyst'),
            '22.226' => array('en' => 'Cash management'),
            '22.227' => array('en' => 'Controlling'),
            '22.228' => array('en' => 'Accountancy'),
            '22.229' => array('en' => 'Financial advice'),
            '22.230' => array('en' => 'Payroll accounting'),
            '22.231' => array('en' => 'Accountancy'),
            '22.232' => array('en' => 'Auditor'),
            '22.233' => array('en' => 'Risk management'),
            '22.234' => array('en' => 'Tax and tax consulting'),
            '22.235' => array('en' => 'Auditing'),
            '22.236' => array('en' => 'Finance and accounting - Other'),
            '23'     => array('en' => 'Legal'),
            '23.237' => array('en' => 'Legal assistant'),
            '23.238' => array('en' => 'Patent law'),
            '23.239' => array('en' => 'Tax / commercial law'),
            '23.240' => array('en' => 'Criminal / civil law'),
            '23.241' => array('en' => 'Legal - Other'),
            '23.242' => array('en' => 'Notary office'),
            '24'     => array('en' => 'Editing, media and information'),
            '24.243' => array('en' => 'Journalism'),
            '24.244' => array('en' => 'Editing and proofreading'),
            '24.245' => array('en' => 'Translation'),
            '24.246' => array('en' => 'Publishing'),
            '24.247' => array('en' => 'Film, TV, radio, multimedia'),
            '24.248' => array('en' => 'Media and information - Other'),
            '25'     => array('en' => 'Security and civil protection'),
            '25.249' => array('en' => 'Fire department / rescue services'),
            '25.250' => array('en' => 'Store security'),
            '25.251' => array('en' => 'Police / prison officer'),
            '25.252' => array('en' => 'Security and civil protection'),
            '25.253' => array('en' => 'Security and civil protection - Other'),
            '26'     => array('en' => 'Sales and commerce'),
            '26.254' => array('en' => 'Field sales', 'de' => 'außendienst'),
            '26.255' => array('en' => 'In-house sales'),
            '26.256' => array('en' => 'Customer service'),
            '26.257' => array('en' => 'Pre-sales'),
            '26.258' => array('en' => 'Technical sales'),
            '26.259' => array('en' => 'Telesales'),
            '26.260' => array('en' => 'Sales'),
            '26.261' => array('en' => 'Sales assistant'),
            '26.262' => array('en' => 'Sales and commerce'),
            '26.263' => array('en' => 'Sales and commerce - Other'),
            '27'     => array('en' => 'Agriculture, forestry, environment'),
            '27.264' => array('en' => 'Animal husbandry'),
            '27.265' => array('en' => 'Environment and nature conservation'),
            '27.266' => array('en' => 'Agriculture, forestry, environment - Other'),
            '28'     => array('en' => 'Civil service / association'),
            '28.267' => array('en' => 'Civil servant with the foreign office'),
            '28.268' => array('en' => 'Civil servant'),
            '28.269' => array('en' => 'Tax administration'),
            '28.270' => array('en' => 'Associations'),
            '28.271' => array('en' => 'Civil service / association - Other'),
            '29'     => array('en' => 'Other'),
            '29.272' => array('en' => 'Other')
        );


    protected $industry =
        array(
            'INDUSTRY_1'  => array('en' => 'Academia'),
            'INDUSTRY_2'  => array('en' => 'Accounting'),
            'INDUSTRY_3'  => array('en' => 'Agriculture'),
            'INDUSTRY_4'  => array('en' => 'Airlines'),
            'INDUSTRY_5'  => array('en' => 'Alternative Medicine'),
            'INDUSTRY_6'  => array('en' => 'Apparel & Fashion'),
            'INDUSTRY_7'  => array('en' => 'Architecture & Planning'),
            'INDUSTRY_8'  => array('en' => 'Arts & Crafts'),
            'INDUSTRY_9'  => array('en' => 'Automotive'),
            'INDUSTRY_10' => array('en' => 'Banking'),
            'INDUSTRY_11' => array('en' => 'Biotechnology'),
            'INDUSTRY_12' => array('en' => 'Broadcast Media'),
            'INDUSTRY_13' => array('en' => 'Building Materials'),
            'INDUSTRY_14' => array('en' => 'Business Supplies & Equipment'),
            'INDUSTRY_15' => array('en' => 'Chemicals'),
            'INDUSTRY_16' => array('en' => 'Civic & Social Organizations'),
            'INDUSTRY_17' => array('en' => 'Civil Engineering'),
            'INDUSTRY_18' => array('en' => 'Computer Games'),
            'INDUSTRY_19' => array('en' => 'Computer Hardware'),
            'INDUSTRY_20' => array('en' => 'Computer Networking'),
            'INDUSTRY_21' => array('en' => 'Computer & Network Security'),
            'INDUSTRY_22' => array('en' => 'Computer Software'),
            'INDUSTRY_23' => array('en' => 'Construction'),
            'INDUSTRY_24' => array('en' => 'Consumer Electronics'),
            'INDUSTRY_25' => array('en' => 'Consumer Goods'),
            'INDUSTRY_26' => array('en' => 'Consumer Services'),
            'INDUSTRY_27' => array('en' => 'Cosmetics'),
            'INDUSTRY_28' => array('en' => 'Daycare'),
            'INDUSTRY_29' => array('en' => 'Design'),
            'INDUSTRY_30' => array('en' => 'E-learning'),
            'INDUSTRY_31' => array('en' => 'Entertainment'),
            'INDUSTRY_32' => array('en' => 'Environmental Services'),
            'INDUSTRY_33' => array('en' => 'Events Services'),
            'INDUSTRY_34' => array('en' => 'Facilities Services'),
            'INDUSTRY_35' => array('en' => 'Financial Services'),
            'INDUSTRY_36' => array('en' => 'Fishery'),
            'INDUSTRY_37' => array('en' => 'Food'),
            'INDUSTRY_38' => array('en' => 'Fundraising'),
            'INDUSTRY_39' => array('en' => 'Furniture'),
            'INDUSTRY_40' => array('en' => 'Glass & Ceramics'),
            'INDUSTRY_41' => array('en' => 'Graphic Design'),
            'INDUSTRY_42' => array('en' => 'Health & Fitness'),
            'INDUSTRY_43' => array('en' => 'Hospitality'),
            'INDUSTRY_44' => array('en' => 'Human Resources'),
            'INDUSTRY_45' => array('en' => 'Import & Export'),
            'INDUSTRY_46' => array('en' => 'Industrial Automation'),
            'INDUSTRY_47' => array('en' => 'Information Services'),
            'INDUSTRY_48' => array('en' => 'Information Technology & Services'),
            'INDUSTRY_49' => array('en' => 'Insurance'),
            'INDUSTRY_50' => array('en' => 'International Affairs'),
            'INDUSTRY_51' => array('en' => 'International Trade & Development'),
            'INDUSTRY_52' => array('en' => 'Internet'),
            'INDUSTRY_53' => array('en' => 'Investment Banking'),
            'INDUSTRY_54' => array('en' => 'Legal Services'),
            'INDUSTRY_55' => array('en' => 'Leisure, Travel & Tourism'),
            'INDUSTRY_56' => array('en' => 'Libraries'),
            'INDUSTRY_57' => array('en' => 'Logistics & Supply Chain'),
            'INDUSTRY_58' => array('en' => 'Luxury Goods & Jewelry'),
            'INDUSTRY_59' => array('en' => 'Machinery'),
            'INDUSTRY_60' => array('en' => 'Management Consulting'),
            'INDUSTRY_61' => array('en' => 'Maritime'),
            'INDUSTRY_62' => array('en' => 'Market Research'),
            'INDUSTRY_63' => array('en' => 'Marketing & Advertising'),
            'INDUSTRY_64' => array('en' => 'Mechanical/Industrial Engineering'),
            'INDUSTRY_65' => array('en' => 'Media Production'),
            'INDUSTRY_66' => array('en' => 'Medical Devices'),
            'INDUSTRY_67' => array('en' => 'Medical Services'),
            'INDUSTRY_68' => array('en' => 'Mining & Metals'),
            'INDUSTRY_69' => array('en' => 'Motion Pictures'),
            'INDUSTRY_70' => array('en' => 'Museums & Cultural Institutions'),
            'INDUSTRY_71' => array('en' => 'Music'),
            'INDUSTRY_72' => array('en' => 'Nanotechnology'),
            'INDUSTRY_73' => array('en' => 'Non-Profit Organization'),
            'INDUSTRY_74' => array('en' => 'Nursing & Personal Care'),
            'INDUSTRY_75' => array('en' => 'Oil & Energy'),
            'INDUSTRY_76' => array('en' => 'Online Media'),
            'INDUSTRY_77' => array('en' => 'Others'),
            'INDUSTRY_78' => array('en' => 'Outsourcing/Offshoring'),
            'INDUSTRY_79' => array('en' => 'Packaging & Containers'),
            'INDUSTRY_80' => array('en' => 'Paper & Forest Products'),
            'INDUSTRY_81' => array('en' => 'Medicinal Products'),
            'INDUSTRY_82' => array('en' => 'Photography'),
            'INDUSTRY_83' => array('en' => 'Plastics'),
            'INDUSTRY_84' => array('en' => 'Printing'),
            'INDUSTRY_85' => array('en' => 'Print Media'),
            'INDUSTRY_86' => array('en' => 'Professional Training & Coaching'),
            'INDUSTRY_87' => array('en' => 'Public Relations & Communications'),
            'INDUSTRY_88' => array('en' => 'Publishing'),
            'INDUSTRY_89' => array('en' => 'Railroad'),
            'INDUSTRY_90' => array('en' => 'Real Estate'),
            'INDUSTRY_91' => array('en' => 'Recreational Facilities & Services'),
            'INDUSTRY_92' => array('en' => 'Renewables & Environment'),
            'INDUSTRY_93' => array('en' => 'Research'),
            'INDUSTRY_94' => array('en' => 'Restaurants & Food Service'),
            'INDUSTRY_95' => array('en' => 'Retail'),
            'INDUSTRY_96' => array('en' => 'Security & Investigations'),
            'INDUSTRY_97' => array('en' => 'Semiconductors'),
            'INDUSTRY_98'  => array('en' => 'Shipbuilding'),
            'INDUSTRY_99'  => array('en' => 'Sports'),
            'INDUSTRY_100' => array('en' => 'Staffing & Recruiting'),
            'INDUSTRY_101' => array('en' => 'Telecommunication'),
            'INDUSTRY_102' => array('en' => 'Textiles'),
            'INDUSTRY_103' => array('en' => 'Translation & Localization'),
            'INDUSTRY_104' => array('en' => 'Venture Capital & Private Equity'),
            'INDUSTRY_105' => array('en' => 'Veterinary'),
            'INDUSTRY_106' => array('en' => 'Wholesale'),
            'INDUSTRY_107' => array('en' => 'Wine & Spirits'),
            'INDUSTRY_108' => array('en' => 'Writing & Editing'),
            'INDUSTRY_109' => array('en' => 'Politics'),
            'INDUSTRY_110' => array('en' => 'Education'),
            'INDUSTRY_111' => array('en' => 'Consulting'),
            'INDUSTRY_112' => array('en' => 'Electrical Engineering'),
            'INDUSTRY_113' => array('en' => 'Energy'),
            'INDUSTRY_114' => array('en' => 'Facility Management'),
            'INDUSTRY_115' => array('en' => 'Gardening/Landscaping'),
            'INDUSTRY_116' => array('en' => 'Geology'),
            'INDUSTRY_117' => array('en' => 'Timber'),
            'INDUSTRY_118' => array('en' => 'Journalism'),
            'INDUSTRY_119' => array('en' => 'Aerospace'),
            'INDUSTRY_120' => array('en' => 'Metrology/Control Engineering'),
            'INDUSTRY_121' => array('en' => 'Metal/Metalworking'),
            'INDUSTRY_122' => array('en' => 'Civil Service'),
            'INDUSTRY_123' => array('en' => 'Pharmaceuticals'),
            'INDUSTRY_124' => array('en' => 'Process Management'),
            'INDUSTRY_125' => array('en' => 'Psychology/Psychotherapy'),
            'INDUSTRY_126' => array('en' => 'Recycling & Waste Management'),
            'INDUSTRY_127' => array('en' => 'Welfare & Community Health'),
            'INDUSTRY_128' => array('en' => 'Tax accountancy/Auditing'),
            'INDUSTRY_129' => array('en' => 'Theater/Stage/Cinema'),
            'INDUSTRY_130' => array('en' => 'Traffic Engineering'),
            'INDUSTRY_131' => array('en' => 'Composites'),
            'INDUSTRY_132' => array('en' => 'Defense/Military'),
            'INDUSTRY_133' => array('en' => 'Public Health'),
            'INDUSTRY_134' => array('en' => 'Transport'),
        );

    protected $job_type = array(
        'PART_TIME' => array('en' => 'PART_TIME', 'de' => 'teilzeit'),
        'FULL_TIME' => array('en' => 'FULL_TIME', 'de' => 'vollzeit'),
        'CONTRACTOR' => array('en' => 'CONTRACTOR'),
        'INTERN' => array('en' => 'INTERN'),
        'SEASONAL' => array('en' => 'SEASONAL'),
        'TEMPORARY' => array('en' => 'TEMPORARY'),
        'VOLUNTARY' => array('en' => 'VOLUNTARY'),
    );


    protected $job_level = array(
        'JOBLEVEL_1' => array('en' => 'Student/Intern'),
        'JOBLEVEL_2' => array('en' => 'Entry Level'),
        'JOBLEVEL_3' => array('en' => 'Professional / Experienced'),
        'JOBLEVEL_4' => array('en' => 'Manager / Supervisor)'),
        'JOBLEVEL_5' => array('en' => 'Executive / VP / SVP'),
        'JOBLEVEL_6' => array('en' => 'Senior Executive / CEO / CFO / President)'),
    );

    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    protected function info($message)
    {
        if (isset($this->logger)) {
            $this->logger->info($message);
        }
        return $this;
    }

    /**
     * makes
     * @param $elem
     * @param $haystack
     * @param null $lang
     * @return null|string
     */
    protected function find($elem, $haystack, $lang = Null)
    {
        $best = '';
        $bestValue = 10000;
        $elems = $elem;
        if (!is_array($elem)) {
            $elems = array($elem);
        }
        foreach ($elems as $elem) {
            foreach ($haystack as $code => $hay) {
                foreach ($hay as $key => $value) {
                    if ($key == $lang || empty($lang)) {
                        // levenshtein ( string $str1 , string $str2 , int $cost_ins , int $cost_rep , int $cost_del )
                        $leven = levenshtein ( strtolower($elem) , strtolower($value),3, 2, 1) + strlen($value) - strlen($elem);
                        if ($leven < $bestValue) {
                            $bestValue = $leven;
                            $best = $code;
                            //$this->info('(lev)' . $leven . ':' . $elem . ' ' . $value);
                        }

                    }
                }
            }
        }
        if ($bestValue < 2)
        {
            return $best;
        }
        return Null;
    }


    /**
     * @param $label
     * @return string
     */
    public function getCategory($label)
    {
        $code = $this->find($label, $this->category);
        if (!empty($code)) {
            return $code;
        }
        $this->info('[xing-category] not found: ' . var_export($label, True));
        return '1';
    }

    /**
     * @param $label
     * @return string
     */
    public function getIndustry($label)
    {
        return 'INDUSTRY_77';
    }


    public function getJobType($label)
    {
        $code = $this->find($label, $this->job_type);
        if (!empty($code)) {
            return $code;
        }
        $this->info('[xing-jobtype] not found: ' . var_export($label, True));
        return 'FULL_TIME';
    }

    public function getJobLevel($label)
    {
        return 'JOBLEVEL_2';
    }

}