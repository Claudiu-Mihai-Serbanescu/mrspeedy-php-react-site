<?php

/**
 * construct/faq.php
 * Single include for all pages. Auto-detects page type from URL/slug and prints EXACTLY 6 relevant FAQs.
 * Optional override before include:  $FAQ_KEY = 'boarding' | 'upvc' | 'car_keys' | 'locks' | 'fire_garage';
 *
 * This file renders Bootstrap 5 accordion + FAQPage JSON-LD.
 */

/** @var string|null $FAQ_KEY Optional category override from the including file */
$FAQ_KEY = $FAQ_KEY ?? null;

if (!function_exists('ms_slug')) {
    function ms_slug(): string
    {
        $uri  = strtolower($_SERVER['REQUEST_URI'] ?? '');
        $file = strtolower($_SERVER['PHP_SELF'] ?? '');
        $slug = basename($file ?: 'page.php', '.php');
        $hay  = $uri ?: $slug;

        if (preg_match('~(burglary|boarding|board|break-?in)~', $hay)) return 'boarding';
        if (preg_match('~\bupvc\b~', $hay)) return 'upvc';
        if (preg_match('~(car-?key|transponder|key-?fob|ignition|vehicle|auto)~', $hay)) return 'car_keys';
        if (preg_match('~(lock-?replacement|lock-?installation|/locks?/|rekey)~', $hay)) return 'locks';
        if (preg_match('~(fire-?door|roller-?shutter|garage-?door|gate)~', $hay)) return 'fire_garage';

        return 'global';
    }
}

$FAQ_SETS = [

    // --- Burglary Repairs & Property Boarding ---
    'boarding' => [
        [
            "q" => "What is property boarding in locksmith emergency work?",
            "a" => "Property boarding is a locksmith emergency service that temporarily secures windows and doors after a break-in or damage, preventing unauthorised access and further weather or vandalism damage until permanent repairs are done."
        ],
        [
            "q" => "How fast can a locksmith respond after a burglary?",
            "a" => "For emergencies we aim to have a locksmith on site in around 30 minutes, 24/7, to secure the property, assess damage and start boarding or lock repairs immediately."
        ],
        [
            "q" => "What should I do right after a break-in before the locksmith arrives?",
            "a" => "Make sure everyone is safe, call the police and get a crime reference number, then call an emergency locksmith. We’ll secure doors, windows and replace or rekey damaged locks."
        ],
        [
            "q" => "Can a locksmith repair or replace damaged locks after a burglary?",
            "a" => "Yes. Our locksmith can repair frames, fit new cylinders and high-security locks, and rekey to make stolen keys useless, restoring your property’s security fast."
        ],
        [
            "q" => "Does emergency property boarding by a locksmith prevent further damage?",
            "a" => "Yes. Boarding shields exposed entry points, reducing the risk of further break-ins, weather ingress and glass hazards until glaziers or builders complete permanent work."
        ],
        [
            "q" => "Are burglary-repair locksmith services available 24/7?",
            "a" => "Absolutely. Our emergency locksmith team operates day and night for burglary repairs, property boarding and urgent lock replacements."
        ]
    ],

    // --- uPVC Repairs ---
    'upvc' => [
        [
            "q" => "What uPVC door and window problems do locksmiths fix?",
            "a" => "A locksmith can fix misalignment, failed multipoint locking mechanisms, worn weather seals, stiff handles and broken hinges that affect security and smooth operation."
        ],
        [
            "q" => "How do I know my uPVC door needs realignment by a locksmith?",
            "a" => "If it drags on the frame, is hard to lock or the latch doesn’t meet the keep, a locksmith realignment prevents further wear and restores easy locking."
        ],
        [
            "q" => "Can a locksmith repair failed uPVC multipoint locking systems?",
            "a" => "Yes. We repair or replace gearboxes, Euro cylinders and keeps, restoring full function and security to uPVC doors and windows."
        ],
        [
            "q" => "How long do uPVC locksmith repairs usually take?",
            "a" => "Most uPVC repairs take a couple of hours depending on the fault and parts availability; our locksmith carries common gearboxes and cylinders on the van."
        ],
        [
            "q" => "What is a multipoint lock and why is it more secure?",
            "a" => "A multipoint lock secures the door to the frame in several points (hooks/bolts), giving better weather sealing and forced-entry resistance than a single latch."
        ],
        [
            "q" => "Can a locksmith replace damaged uPVC panels without changing the whole unit?",
            "a" => "Often yes. A locksmith can replace individual cylinders, gearboxes, handles and some panels without full door or window replacement, saving time and cost."
        ]
    ],

    // --- Car Keys / Programming ---
    'car_keys' => [
        [
            "q" => "What should I do if I lose my car key — can an auto locksmith help?",
            "a" => "Call an auto locksmith. We cut and program replacement keys on site and can erase lost keys from the vehicle so they no longer work."
        ],
        [
            "q" => "Can an auto locksmith replace any type of car key?",
            "a" => "Yes — standard keys, transponder keys, remote key fobs and smart keys for many makes and models, often faster than a dealership."
        ],
        [
            "q" => "How long does a car key replacement take with an auto locksmith?",
            "a" => "Typically 30–60 minutes depending on the vehicle and key type; programming complexity varies by manufacturer."
        ],
        [
            "q" => "My key fob stopped working — can an auto locksmith fix it?",
            "a" => "Usually yes. We test the fob, replace batteries, reprogram or supply a new fob so it syncs with your immobiliser."
        ],
        [
            "q" => "Can you program a new key if the old one was stolen?",
            "a" => "Yes. The auto locksmith programs a new key and removes the stolen key from the car’s ECU to prevent unauthorised starting."
        ],
        [
            "q" => "Do I need a dealership or can a mobile auto locksmith handle it?",
            "a" => "A mobile auto locksmith can handle it on site, saving towing and often costing less than a dealer."
        ]
    ],

    // --- Lock Replacement & Installation ---
    'locks' => [
        [
            "q" => "When should I call a locksmith to replace my locks?",
            "a" => "After moving home, losing a key, a break-in or if locks are worn or sticking. A locksmith can also upgrade you to higher-security cylinders."
        ],
        [
            "q" => "How long does a locksmith take to change a lock?",
            "a" => "Around 30 minutes per standard lock, depending on door type and any adjustments required."
        ],
        [
            "q" => "What types of locks can a locksmith replace or install?",
            "a" => "Nightlatches, Euro cylinders, deadbolts, smart/keyless locks and high-security cylinders for both residential and commercial doors."
        ],
        [
            "q" => "Are there locks more resistant to break-ins?",
            "a" => "Yes — anti-snap, anti-pick and anti-drill cylinders. A locksmith can recommend the best certified option for your door."
        ],
        [
            "q" => "Do I need to change all locks after losing a key?",
            "a" => "A locksmith can rekey or change the cylinder so the lost key no longer works — usually cheaper than new hardware."
        ],
        [
            "q" => "What’s the difference between rekeying and replacing a lock?",
            "a" => "Rekeying changes the internal pins to a new key; replacement installs a new lock body/cylinder. Your locksmith will advise which fits best."
        ]
    ],

    // --- Fire Doors / Roller Shutters / Gates / Garage Doors ---
    'fire_garage' => [
        [
            "q" => "Why are fire doors important and how can a locksmith help?",
            "a" => "Fire doors slow the spread of smoke and fire. A locksmith/door technician ensures compliant closers, latches and hardware for safe performance."
        ],
        [
            "q" => "How often should fire doors be inspected?",
            "a" => "At least annually (more often in commercial sites). A qualified technician or locksmith checks gaps, seals, hinges and self-closers."
        ],
        [
            "q" => "Can fire doors be customised without losing protection?",
            "a" => "Yes, with certified leafs and compatible hardware. A locksmith can fit approved locks and panic devices that retain the rating."
        ],
        [
            "q" => "How do roller shutters improve security?",
            "a" => "They form a strong physical barrier. A locksmith/engineer can service locks, motors and controls to keep them reliable."
        ],
        [
            "q" => "Do roller shutters and automatic gates need maintenance?",
            "a" => "Regular inspections and adjustments prevent failures and extend lifespan; a locksmith/engineer can set a service plan."
        ],
        [
            "q" => "What gate options exist for homes and businesses?",
            "a" => "Manual or automatic metal/wood gates with access control; a locksmith integrates locks, keypads and intercoms for secure entry."
        ]
    ],

    // --- Fallback generic ---
    'global' => [
        [
            "q" => "Are your locksmith services available 24/7?",
            "a" => "Yes — our emergency locksmith team handles lockouts, break-ins and urgent repairs day and night."
        ],
        [
            "q" => "How fast can a locksmith arrive?",
            "a" => "We target ~30 minutes across Birmingham & West Midlands, subject to traffic and exact location."
        ],
        [
            "q" => "Do you offer upfront locksmith pricing?",
            "a" => "Yes. We provide a clear estimate before work — no hidden fees."
        ],
        [
            "q" => "What areas does your locksmith cover?",
            "a" => "Birmingham and surrounding West Midlands districts — see the Areas section for details."
        ],
        [
            "q" => "Do you repair as well as replace locks?",
            "a" => "Absolutely. Our locksmiths repair, rekey and upgrade to high-security hardware, including uPVC multipoint systems."
        ],
        [
            "q" => "How can I book a locksmith?",
            "a" => "Call for emergencies or use the contact form to schedule a visit."
        ]
    ],
];

// decide set (prefer override if valid)
$override = (is_string($FAQ_KEY) && isset($FAQ_SETS[$FAQ_KEY])) ? $FAQ_KEY : null;
$key      = $override ?: ms_slug();
$items    = $FAQ_SETS[$key] ?? $FAQ_SETS['global'];

// keep exactly 6
$items = array_slice($items, 0, 6);

// Unique IDs to avoid Bootstrap collisions
$slug   = basename($_SERVER['PHP_SELF'] ?? 'page', '.php');
$suffix = substr(md5(($slug ?: 'page') . $key), 0, 6);
$accId  = "faqAccordion-$suffix";

// Build FAQPage JSON-LD
$faqSchema = [
    "@context" => "https://schema.org",
    "@type"    => "FAQPage",
    "mainEntity" => array_map(function ($it) {
        return [
            "@type" => "Question",
            "name"  => $it['q'],
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text"  => $it['a'],
            ],
        ];
    }, $items),
];
?>

<section id="faq" class="container mt-4">
    <h2>Frequently Asked Questions</h2>
    <div class="accordion" id="<?= htmlspecialchars($accId, ENT_QUOTES) ?>">
        <?php foreach ($items as $i => $it):
            $q   = htmlspecialchars($it['q'], ENT_QUOTES);
            $a   = $it['a'];
            $aSafe = htmlspecialchars($a, ENT_QUOTES);
            $hid = $accId . '-h' . ($i + 1);
            $cid = $accId . '-c' . ($i + 1);
        ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="<?= $hid ?>">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $cid ?>"
                        aria-expanded="false" aria-controls="<?= $cid ?>">
                        <?= $q ?>
                    </button>
                </h2>
                <div id="<?= $cid ?>" class="accordion-collapse collapse"
                    aria-labelledby="<?= $hid ?>" data-bs-parent="#<?= $accId ?>">
                    <div class="accordion-body"><?= nl2br($aSafe) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script type="application/ld+json">
        <?= json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
    </script>
</section>