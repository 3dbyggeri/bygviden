<?php
$ip = $_SERVER['REMOTE_ADDR']; 
$ref= $_SERVER['HTTP_REFERER'];
$ua = $_SERVER['HTTP_USER_AGENT'];

$msg = date("d.m.Y H:i:s ") ."\t".$ip."\t".$ref."\t".$ua."\n";

$handle = fopen('spider.txt', 'a');
fwrite($handle, $msg);
fclose($handle);
?>
<html>
  <head>
    <title>test dimmer nemmer</title>
  </head>
  <body bgcolor="#FFFFFF">

pythonware.com 
products ::: library ::: search ::: daily Python-URL! 

Products

Library

Downloads

Support

About Us

Search
 
 

Daily Python-URL

Daily news from the Python universe, presented by your friends at PythonWare.

2005-02-23

Chris Withers @ Zope.org: February Zope Bug Day [The eighth Zope Bug Day is on Friday 25th February.]

 Anand Pillai @ Python Cookbook: Series generator using multiple generators & function decorators [This is a rewrite of my previous recipe "Series generator using generators & function decorators". Instead of returning the series as a list, this one creates another generator instead.]

 Jonas Galvez @ Python Cookbook: SimpleWrapper [A simple object wrapper that allows you to define pre-defined parameters for functions (global for all functions associated with the object).]

 Anand Pillai @ Python Cookbook: Series generator using generators & function decorators [An example which shows the power of decorators when combined with generators. This recipe allows you to generate different kinds of series of numbers by applying a decorator over an infinite integer generator. A processing function and a condition function can be used to specify the rules.]

 Chris Perkins @ Python Cookbook: Binary search with the bisect module [Writing a binary search algorithm is surprisingly error-prone. The solution: trick the built-in bisect module into doing it for you. The documentation for bisect says that it works on lists, but it really works on anything with a __getitem__ method. You can exploit this fact to make bisect work in ways that you may not have thought of.]

 Jeffrey Shell @ Industrie Toulouse: Loud Thinking, agile web development, etc. [I just added Loud Thinking to my subscriptions list, and hope to have time to actually go through and read it soon. Loud Thinking is David Heinemeier's weblog. Heinemeier is a big dynamic-language advocate and is responsible for Ruby On Rails, a Ruby web application toolkit that's getting considerable buzz lately. I'm not blown away with Rails. I'm personally more interested in toolkits like Seaside and Wee. Many other pieces of Rails I've seen done in Bobo / Zope to varying degrees. I still believe that Bobo, aka the "Python Object Publisher" is a significant piece of web technology and is still a fundamental part of Zope 2 and 3, wherein an HTTP (or FTP, or other) request is translated into an object call. The Python Object Publisher is an Object Request Broker in this sense. Beautifully simple and powerful. Don't let the complexities of the systems built on top of it (Zope 2, etc) fool you. Anyways, Heinemeier seems to have a lot of interesting ideas and is willing to happily talk about them. Contrary to what you may read in trade rags, the web is not dominated by J2EE. Almost all highly popular and/or innovative web sites are implemented in, or were prototyped in, an agile language such as Python, Ruby, Lisp, or Perl.]

 Ian Bicking: Another plan: SQLObject 0.7 [A while ago I wrote some thoughts on what SQLObject 0.6 might look like. Well, a lot of those things didn't happen (actually nearly all of them), though the plans were pretty speculative so it's no surprise it didn't happen. But I'm going through another set of refactoring for SQLObject 0.7 (and I'm already part way through it) so I can say better what will happen for that version.]

 Will Guaraldi's Blog: PyBlosxom contributed plugins version 1.1 [In lieu of other solutions, I'm going to start releasing contributed plugin packs. This one should work with PyBlosxom 1.1. If this works out, then I'll continue releasing contributed plugin packs that match up to PyBlosxom.]

 Ian Bicking: FileSystemView vs. LocalFS [From a comment in my last post I looked at FileSystemView as an alternative to LocalFS. FSView certainly feels cleaner, but I'm not 100% sure. Here's my initial thoughts.]

 Zope.org: Windows configuration differences [This document covers some of the differences that you'll encounter installing Zope X3 on Windows compared to installing Zope 2.x. It is meant to help Zope admins who are used to using Zope 2.x on the Windows platform, but it should also be useful to first-time Zope admins.]

 Zope.org: ZopeX3-3.1.0 to do [Here is a list of things that need to be done before a 3.1 release.]

 2005-02-22

Peyton McCullough @ Dev Shed: Introduction to Jython [Java and Python - each have their advantages and disadvantages. What is a programmer to do if he wants ease, efficiency, and power? Believe it or not, there is a solution - a way to merge both languages and get the best of both worlds.]

 Ryan Tomayko: Fish, bad [I've been enjoying David Heinemeier's Loud Thinkning blog lately. The kid has excellent chin, which seems to be a requirement for dynamic-language advocates mingling with the static language community. In his latest, "Serving the koolaid with the facts", he takes on some of the criticism coming out of the Java weblogs that targets various aspects of Rails that go against conventional static-language thinking. There are so many aspects of dynamic language programming that really just don't work in a static language environment and the opposite is also true; one is an ocean and the other dry land. Unfortunately, this isn't immediately apparent and those with strong backgrounds in one environment have a tendency to misjudge the viability of approaches in another. "Fish? That will never work." I think the key to overcoming this problem is just to keep on talking. Over time it will become apparent that you can't measure dynamic language approaches under the static language microscope and then maybe we can just get on with things and start figuring out where the two environments can work together. It is perhaps not an accident that some of the most beautiful places in nature are the coasts where two strange environments meet.]

 Will Guaraldi @ Will's Blog: Changing the requirement to Python 2.2? [Steven's been doing development on PyBlosxom to allow for other frameworks than plain CGI. The architecture changes he's making solve some other issues as well. The problem we've bumped into is that one of the things he wants to do requires us to change the minimum Python version from 2.1 to 2.2. (Bill noted that it's likely that PyBlosxom won't work in 2.1 as it is now anyhow.) So the question is, would it be OK to change the minimum requirements?]

 Bill Bumgarner: Getting started with Python on Mac OS X [A friend asked me what he should install onto his Mac OS X system to most effectively learn Python. This particular person is a very experienced Objective-C and Java developer, with loads of Mac OS X specific adventures. This post is targeted to that kind of developer.]

 Männer Club: No software patents! A portlet for Plone [Under the influence of the patent system and big industry lobbyists, the European Union is on the verge of making a huge mistake: to pass a law that would legalize software patents. With the use of this portlet, it is possible to contribute to the prevention of software patents.]

 Thomas Hinkle @ Python Cookbook: MarkupString [A subclass of String that allows simple handling of Pango markup or other simple XML markup. The goal here is to allow a slice of a Python markup string to "do the right thing" and preserve formatting correctly.]

 Afpy - French Python & Zope User Group [We are happy to announce the birth of Afpy, a French-speaking Python & Zope user group.]

 Hans Nowak @ Efectos Especiales: Anti-patterns [Anti-patterns. Hmm, I wonder, is Wax a case of abstraction inversion?]

 Titus Brown @ Advogato: A generic Python blog entry [Using decorators to make Python free-threading through bypassing the global interpreter lock doesn't require static typing, so why does Guido like the '@' notation?]

 Swaroop C H @ The Dreamer: First official meetup of the BangPypers [I am amazed at the number of messages asking me about the photos and a blog post on Saturday's meetup of the BangPypers - so here it is.]

 Jonathan Riddell @ KDE Dot News: FOSDEM 2005: Python bindings interview [Simon Edwards will be talking about "KDE application development using Python" in the FOSDEM KDE Developer's Room. In the interview here he talks about the advantages of Python, how it compares to other languages, and whether KDE should be rewritten in Python.]

 Max Ischenko @ Max's Blog v0.2.1: Project postmortem [As my Mr Postman project reached its 1.0 milestone I decided to run a postmortem. This post contains some parts of the lengthy postmortem document I wrote.]

 (render-blog Ng Pheng Siong): Being agile [A 2005 thread in comp.lang.python, "Low-end persistent strategies", began thusly: "I've started a few threads before on object persistence in medium- to high-end server apps. This one is about low-end apps."]

 2005-02-21

Vaults of Parnassus: REPPY Report generator [reppy is a PDF-report generator for databases written in python. It is based on a description of the report in an XML-template and brings an GUI -Tool (xtred) to create or modify such templates.]

 Ian Bicking: Zope to LocalFS [At my work we're in this kind of weird limbo, where a lot of our newer projects are in Webware and we have a lot of stuff left in Zope. And while some of it could really use a rewrite anyway - especially the stuff from the Bad Old Days of pure DTML applications - rewriting that stuff doesn't really accomplish anything. Anyway, some new little app came up, and I figured I'd stick to Zope because it was just too small to do in Webware, and we haven't completely figured out how we're doing Webware deployment. I decided I'd do a little experimentation in Zope, and see if I could put the whole application into LocalFS - which means all the files would be on the disk.]

 Grig Gheorghiu @ Agile Testing:  Articles and tutorials [My posts are starting to be archived and hard to find, so I thought of putting up this page with links to the various articles and tutorials that I posted so far.]

 Jarno Virtanen @ Python Owns Us: Lesser known historical tidbit of Python [Most of us know, and some have even read, the original announcement of a new programming language called Python back in 1991 by Guido van Rossum. But did you know that shortly before that Guido wrote a rather apologetic response to being flamed about non-source posting to alt.sources.d?]

 Andrew Kuchling @ AMK's Journal: Python/Zeroconf article [I probably spent an entire day this past week figuring out the PyZeroconf module and debugging my daemon that used it. Eventually I figured out that the problems stemmed from bugs in both my code and in PyZeroconf; also, bare 'except:' statements are evil. (But you already knew that.) To save other people from spending their time and brain cells, this morning I wrote up an article on Zeroconf and Python.]

 Jim Hughes @ Feet Up!: Traffic cams on your phone [Russ posted about a Flash Lite application for Series 60 that displays recent images from New York City traffic cameras on your phone. Not to be outdone Christopher Schmidt had a quick play at writing a similar app in Nokia's Series 60 Python; the result is this Traffic Cam Proof Of Concept written in about 1 hour 20 minutes! It's a nice app, but not being in New York it's of little use to me, fortunately the BBC's Travel News page has a load of traffic cams, so I grabbed the BBC's list of London traffic cams and hacked up a London variant of Chris's app.]

 Jarno Virtanen @ Python Owns Us: Python whitespace FAQ, or, Python is not Fortran 77 [It's really rather a shame that I should write a page like this in these modern, wise times, but I have heard people mentioning this stuff now and then.]

 Swaroop C H @ The Dreamer: Coming soon: Learn Python in German! [Lutz Horn, Bernd Hengelein and Christoph Zwerschke have volunteered and started to translate my Python beginner's book to German! Thanks for taking up this effort!]

 David Ascher's Blog: Nouvelle Python cuisine [I've just had the pleasure of doing a technical review of the second edition of the Python Cookbook (my name's on the cover for historical reasons, but Alex and Anna did all of the editing for this second edition). This new edition, in addition to incorporating new and updated recipes from the online cookbook, focuses on today's Python, not the historical amalgam which the community uses in practice. That editorial choice makes the book a very different book than the last, which was edited at a time of transition for Python. Python is certainly a bigger language than when I started – but it really does feel like it's stayed true to its nature over the years, and Alex and Anna did a great job keeping the cookbook Pythonic.]

 Christopher Schmidt @ Technical Ramblings: Development time [Russ posted about a pretty cool Flash Lite application that was developed: a way to look at the NYC traffic cameras using your phone. It's an extremely cool app - if I lived in New York, I'd buy Flash Lite just to be able to use that application. One thing that Russ mentioned was the development time for the project: 20 hours of development time, when something in Java would have been way larger. Well, I'm a Python man, not a Flash man, so I can't get much out of this yet. However, I do think it's a cool use case: so I did a little research, found out where the data that the Flash app uses was coming from, and did a little hacking. The result? TrafficCam version 0.1, in Python. This little app took me 45 minutes to develop a fully functional prototype. So, although Flash is great for pretty apps, Python can be really great for quick apps, especially on the phone. With another 35 minutes of work, I now have fully functioning tabbed lists, one for each borough.]

 2005-02-18

Sean McGrath: Jython and numeric directory names and a segue into aspiration mode [I want to import Python code from a module. No problem. In Jython, you add the directory name to python.path in your registry, create the all important __init__.py file and your done. Works great. Except when the directory name is numeric. Then the import fails. It took me an hour to track the problem down as I assumed the problem was in the __init__.py or other machinery. It never occured to me that the directory name would be the nub of the problem. This universe has countless zillions of programmer hours spent diagnosing problems like this. By writing this one down I am doing my teeny weeny bit for progress. I aim to fractionally increase the chances of somebody else with the same problem getting a Google search hit before they spend the hour I just spent. If we all do this kind of thing every day - factor it into our daily routines - the benefits will be disproportionatly positive for this profession and its ability to debug not only code, but itself.]

 Andrew Kuchling @ AMK's Journal: Storage formats run amok [I decided to measure some numbers to answer Fredrik's question about my previous weblog entry, and came across something amazing. I decided to figure out how much space was being used to store links between components. One model has about 700 links, which becomes a 700-element Python list. In S-expression form, that comes out to around 130K; it's 200K as a pickle. In the old format, links were stored in many files scattered across directories, amounting to some 900K of XML. That's 600% overhead! I suspect the old representation contains a fair bit of junk - fragments of links that once existed, but weren't cleaned up completely on deletion. So my comparison is very slightly unfair. I don't really care, though - if a data structure is so complicated you can't update it correctly, quibbling about overhead is pointless.]

 Ian Bicking: Strange and unprofessional [Ryan Tomayko complains about an article calling Ruby web development "strange and unprofessional". "Strange and unprofessional" - hold that up as a compliment! It's just another way of saying "imaginative and alive". Woohoo, to hell with the enterprise! Let's all say together! I've been having a lot of fun with the ChiPy lately, we've got a pretty good group going. There's actually a number of us using Python in our jobs, but the group isn't professional. Those of us who use it in our jobs do so because we have autonomy, and there's not much difference between that and the hobbyists, because either way we enjoy what we're doing. It's fun because it's not professional. Java groups are professional and they are booooring. Professionalism is a facade that allows you to build working relationships. But we can base our relationships on better things than professionalism.]

 Christopher Armstrong @ Twisted Radix: Twisted Sprint [Are you coming to the Twisted Sprint in Hobart? A spectacular cast and a hard-working crew will make this event incredibly stupendous.]

 Ryan Tomayko: Web dominated by J2EE? [I have mixed feelings about this article from IBM developerworks. The author describes how to build a simple guest-book application in Ruby using the Cerise application server. I love seeing dynamic languages get exposure on the bigger developer sites but the article presents dynamic languages as both "useful and powerful" and at the same time "strange and unprofessional". I see this kind of shit all the time and it drives me crazy: "With a World Wide Web dominated by J2EE application servers, choosing another Web framework can seem strange or unprofessional." Gah! What World Wide Web is the author referring to?]

 Aaron Brady @ VirtualVitriol: Futures, or "Using Python to expand your mind" [I developed a small futures implementation in Python. The reason I wanted to write it was to try to illustrate "tipping points", and how learning different languages can really expand how you develop in others. When I started learning Python, the ubiquity of lists, tuples and dictionaries really impressed me. I found that there were countless places to use them, whereas in Java I would have previously gone and created a new class. I learned Java before Python, and its idea of OO had become "the" idea of OO in my head. I had to go learn that you don't need complicated hierarchies in OO Python - because duck typing allows you to side-step the static typing issues in many cases. In my code, things only inherit if they actually have to re-use functionality, but in Java I had lots of abstract classes and interfaces so that I could make (say) lists of Action objects. That kind of subtle difference makes up a lot of the speed advantages that people cite when moving to Python. But that's not what I want to talk about - I want to talk about the advantages learning Python brings to Java.]

 Sridhar Ratn @ Infinite Thoughts: Nevow HTML documentation from svn [Nevow svn has a lot of good documentation. Thanks to fzZzy for his excellent documentation. I have made the HTML version of it available.]

 Fredrik Lundh @ online.effbot.org: Substring search performance, again [I recently discovered that the new "substring in string" operator can be a lot slower than "string.find(substring)", which is a bit puzzling. With the search string "not there", and the target string "not the xyz" * 100, I get the following results: "str in" - 25.8 µsec per loop, "str.find()" - 6.73 µsec per loop. As it turns out, "in" doesn't use the same code as "find"; it's a less efficient reimplementation of the same brute-force algorithm. With a little luck, this will be fixed in the next 2.4 release.]

 James Tauber's Blog: Leonardo 0.5.0 released [I'm pleased to announce that version 0.5.0 of my Python blog/wiki/CMS software Leonardo has been released.]

 Andrew Kuchling @ AMK's Journal: Why so quiet? [I've been rather quiet for the last week. Basically, my energies have been going into work-related things. Starting in January we embarked on a massive rewrite of our code, and I'm responsible for a significant portion of the design. It's a break with the past, discarding two large chunks of code: an existing C++ front-end GUI and a complicated set of XML files representing the data. They're replaced by a PyGTk front-end and a set of Python objects stored in a Durus database. So far I'm immensely pleased with how things are working out. In the past few weeks a remarkable amount of complicated code has dropped away.]

 Slashdot: Linux-based cat feeder [Chris McAvoy is a Unix administrator and an owner of two cats. So as a natural application of his work to his hobby he built this Linux-based cat feeder. A little hardware hacking and Python scripting can get you a device that would automatically disperse a yummy fish at specified intervals.]

 Tony Byrne @ CMSWatch: Twenty to watch in 2005 [CMS Watch's 3rd annual PeopleWatch list identifies 20 people who could make a difference on the CMS landscape in 2005. One of those is Paul Everitt, Founder and Product Leader of Zope Europe Association.]

 2005-02-17

Enfold Systems: Oxfam America's Plone-powered website raises 14 million in tsunami relief [Enfold Systems launched a website for Oxfam America powered by Plone. Three months of development and two months of acceptance testing resulted in improved functionality, performance and usability of the website - which integrates seamlessly with Oxfam's third-party online donation and advocacy system. A primary goal of the site was to implement a system that could scale to meet Oxfam's growing organization and donor base for years to come. "In the course of ten days during the tsunami crisis, Oxfam had almost half of its typical yearly visits, and almost 1/3 of its yearly bandwidth - the system performed beautifully", said Internet Manager Nicholas Rabinowitz.]

 Sean McGrath: A zen-of-Python moment [Today, I am working on a system that will be invoked like this: "jython driver1.py build_parameters1.py". That one line pretty much sums up my Pythonic relationship with code and data these days. Hint, an earlier incarnation of this system had: "jython driver1.py build_parameters1.xml".]

 Swaroop C H @ The Dreamer: Learn Python in Chinese!! [Juan Shen has translated my beginner's book on Python, 'A Byte of Python', to Simplified Chinese! Juan Shen is a postgraduate at Wireless Telecommunication Graduate School, Shanghai Jiao Tong University, China PR. More details about Juan Shen and his efforts to spread Python in China is in a appendix in the translated version.]

 Bob Ippolito @ from __future__ import *: New PyObjC protocols [PyObjC now has a new protocol that makes it significantly easier to handle the special cases of Python -> Objective-C bridging. This has far-reaching implications. I'm pretty certain that I've been able to remove hundreds of lines of code in just 'NSNumber' support with this generalization. Speaking of protocols, I also added support so that the formal ones work!]

 Guyon Moree @ Gumuz' Devlog: OSS Python projects: First impression [I was truly overwhelmed by the number of responses I got on my previous post. Besides the useful advice that I got, I was also introduced to a number of excellent Python projects that I hadn't heard of before or didn't look at well enough. I picked a few projects that interested me and began to take a look at them more closely - iPodder, Trac, Subway and CherryPy.]

 Swaroop CH @ The Dreamer: BangPypers meet on Saturday [If you live in Bangalore (or happen to be in Bangalore the day after tomorrow) and want to learn about the Python programming language, join us at the BangPypers meet on Saturday! It's going to be a fun day. What's gonna happen? "Introduction to Python", "Functional programming using Python", and "Generators & generator expressions".]

 Titus Brown @ Advogato: socal-piggies [Grig is organizing a Southern California Python Interest Group, and, as usual, I'm sticking my nose into it.]

 James Tauber's Blog: Amazing Python hack [Ferdinand Jamitzky's "Infix operators" recipe in the ASPN Python Cookbook has to go down as the best Python hack I've seen. Be sure to read the discussion too.]

 Andrew Kuchling @ AMK's Journal: GvR speaking in Palo Alto on Thursday [Guido is giving a talk on Thursday evening in Palo Alto. The talk is titled "Python: Building an open-source project and community". I don't believe Guido has spoken on this topic before; it would be interested to hear his assessment of how the Python community is working. Maybe this will be his keynote at PyCon.]

 Titus Brown @ Advogato:  [The Discover article I mentioned is online at the author's Web site. I do believe that they're working on a scripting interface for Python, partly due to my proselytizing, but I can't find any mention of it on the Web sites; I'll ask.]

 Grig Gheorghiu @ Agile Testing: Agile Documentation with doctest and epydoc [This post was inspired by an article I read in the Feb. 2005 issue of Better Software: "Double duty" by Brian Button. The title refers to having unit tests serve the double role of testing and documentation. Brian calls this Agile Documentation. For Python developers, this is old news, since the doctest module already provides what is called "literate testing" or "executable documentation". However, Brian also introduces some concepts that I think are worth exploring: Test Lists and Tests Maps.]

 mikewatkins dot net: Sometimes too much open source... ["An acquisition, search and retrieval system based on Zope/Plone" is a case study that follows a group as they examined, prototyped / discarded approaches, and eventually implemented a relatively high-volume image-capture solution. I found this case study quite interesting since it's in the field I spent most of the last decade. My quick conclusion is that they over-relied on open-source tools to get the job done; I also wonder about the choice of Zope for such a first application. It's almost as if they were trying to force fit a solution.]

 Ian Bicking: Re: Falcon [Just saw an announcement about Falcon, a new programming language. Reading the documentation, it seems quite reminiscent of Python, or maybe it's just that I'm not getting a wide enough perspective. Anyway, following similar posts on Boo and Prothon, here's the differences with Python that I can see. Considering its similarity to Python (since there's many more points where they are identical, compared to these points where they differ) it would be nice to see a why-Falcon-instead-of-Python document or something. If this were a language based on Parrot (if it was ready), or .NET, or the JVM, it could be a plausible language to use. I don't really get it as a standalone language, though - the investment cost is too high for very little return.]

 2005-02-16

Phillip Eby @ Dirt Simple: Making it from scratch (with TDD and Python) [The last couple of weeks, I've been working on a prototype platform API for Chandler, called "Spike". My design goal was to create a "dirt simple" API for developing Chandler content types, and so I chose to create Spike "from scratch", using a test-driven development approach and no libraries except for what already comes with Python 2.4. The Spike schema API may not be the tiniest domain metamodelling facility I've ever built, but it's probably one of the most featureful, as well as one of the smallest in terms of code size, and simplest in internal implementation. It is quite simply a thing of beauty, an irridescent jewel of interlocking features. A few weeks ago I was reading Christopher Alexander's classic article, "A City is not a Tree", which describes the principle of overlap in design, so perhaps my design choices in Spike were subconsciously influenced by it. Alexander points out that the life of a city (and the beauty of a lot of art) comes from the overlaps it contains, where adjoining areas share a common functional part. He argues for cities whose structure is more like a semilattice and less like a hierarchy (i.e. not a tree). This is the same kind of conceptual density that Python itself has, where simple concepts like calling, iteration, mappings, sequences, and attributes occur over and over again, reused for different purposes. My hope is that these concepts of repetition and simplicity will make the Spike API very easy to learn and use, in the same manner that Python itself is.]

 Phillip Pearson @ Second p0st: Python Job: e-learning XML Editor (University of Auckland) [I just got a message from a guy at the University of Auckland, Brent Simpson, who is looking for a Python programmer to hire on a fixed-term contract to do some Python/JavaScript hacking on a web project, and perhaps do some XUL/JavaScript work inside a Mozilla-based application (I think).]

 Richard Jones' Stuff: I take back all I said about __iter__ators [Some time ago, I questioned the wisdom of adding the new iterator protocol to Python. Now I'm wiser, and I understand :) Roundup 0.8 includes per-item access controls - for example, you can specify that users may only view / edit certain issues, or perhaps certain messages attached to issues. The HTML templating system now automatically filters out inaccessible items from listings. In one situation, it does so using an iterator. Doing this using an old-style __getitem__ "iterator" would be much more difficult and messy.]

 Kevin Dangoor @ Blue Sky on Mars: Cheetah template tip: you want _namemapper for Windows [I was testing out my latest build on Windows and found that it was very slow. I tossed a timer onto the page and found that it was taking 21 seconds to render. That was when I remembered that the setup.py script for Cheetah assumes that you don't have a C compiler if you're running Windows. I'm working with enough modules that it was well worth getting a functional MinGW setup going. I changed Cheetah's setup.py to enable the _namemapper C module. The rendering time for that page dropped to 0.06 seconds, 350 times faster. It sure seems like you need _namemapper for any but the most trivial templates.]

 Peter Bengtsson @ Peterbe.com: Optimize plone.org with slimmer.py [If you do a speed report on plone.org you notice that the three biggest files it serves are plone.css, plone_javascripts.js and index.html. If you run these through my little slimmer whitespace optimizing program in Python you prevent serving 26Kb.]

 Jonathan Riddell @ KDE Dot News: FOSDEM 2005: Scribus in the commerical DTP world [In the second in KDE Dot News's series of interviews with speakers in the FOSDEM KDE developers room, Scribus developers Craig Bradney and Peter Linnell talk about the state of desktop publishing on Unix and its acceptance in the commercial DTP world. One of the strengths claimed for Scribus is its cross-platform Python scripting. "This is a sleeper as Python allows you to extend Scribus with the tons of Python modules available. Cross-platform scripting in pro DTP apps is almost nonexistent."]

 Alan Green @ Nu Cardboard: March 10 is Python Meetup Day [7:00pm on the second Thursday of each month is the default time for Python meetup groups all over the world. That's March 10 for next month. I suppose it's supposed to make you feel warm and fuzzy, knowing that there are little groups of true believers congregating at the same time, all over the world.]

 2005-02-15

Marius Gedminas @ Random Notes from MG: How to use Zope 3 generations [When you're developing a Zope 3 based application, and you want to make old object databases continue to work as you change object attributes, you should consider Zope 3 database generations.]

 mikewatkins dot net: Living with SQLObject's cache [Recently I've had a reason to look around again at Python ORM land again. SQLObject takes the prize for overall speed and quick convenience when doing something quick and dirty. I was struggling with one issue however: updates in another process will not be reflected in an already running process unless you force SQLObject to skip caching.]

 Ferdinand Jamitzky @ Python Cookbook: Infix operators [Python has the wonderful "in" operator and it would be nice to have additional infix operators like this. This recipe shows how (almost) arbitrary infix operators can be defined.]

 Jim Jinkins @ Python Cookbook: Generate a Windows command file to execute a Python program [Generate a Windows command file that executes a Python program. Typing 'my_prog arg1 is easier than typing 'python C:\PyLib\my_prog.py arg1'. Needed because Windows does not support '#!/bin/env python' as the first line of the program.]

 Phillip Pearson @ Second p0st: Optimising how you write your code so SQL doesn't hurt so much to use [I used to not like using SQL, because it meant so many lines of code to do anything. Now I have some helper functions, and just about anything is very easy.]

 Martin Blais @ Python Cookbook: Generic proxy object with before/after method hooks [A proxy object that delegates method calls to an instance, but that also calls hooks for that method on the proxy, or for all methods. This can be used to implement logging of all method calls and values on an instance.]

 Sean McGrath: Jython Wiki watching [Watching the new and fantastically wonderful Jython Wiki is probably going to be a popular way of keeping up to date with Jython developments.]

 Sridhar Ratna @ Infinite Thoughts: Nevow with CGI and WSGI adaptor [With 'dp' publishing the Nevow deployment docs in svn, I could easily get this "Hello world" Nevow application working with the CGI and WSGI adapter.]

 2005-02-14

Sridhar Ratna @ Infinite Thoughts: Twisted 2.0 alpha [Twisted 2.0 alpha release is unofficially made unavailable by radix! Twisted 2.0 (unlike 1.3) is split into components, so that you can install the components of your choice, instead of installing the whole twisted package. ]

 Sridhar Ratna @ Infinite Thoughts: Experience with Python web frameworks [I had decided to write my own blog in Python. But my experience at trying out the Python web frameworks came out bad.]

 Max Ischenko @ Max's Blog v0.2.1: A pressure for concurrency [Herb Sutter published an article called "The free lunch is over: A fundamental turn toward concurrency in software". It's a fascinating read in which he argues we (the programmers) are now facing the next major paradigm shift since the introduction of object-oriented programming. The reason? "Applications will increasingly need to be concurrent if they want to fully exploit CPU throughput gains." And this is caused by the new hardware trend towards multicore systems. It will be interesting to watch how tooling vendors react to this trend. For instance, CPython has an infamous GIL which looks like a real obstacle for Python programs on multicore systems.]

 Bob Ippolito @ from __future__ import *: Nascent py2app documentation [I started on some py2app documentation for the next release.]

 Hans Nowak @ Efectos Especiales: Some people are actually using Wax ^_^ [ConfigEditor is a simple configuration file editor.]

 Peter Bengtsson @ Peterbe.com: Python optimization anecdote [I've learned something today. The 'cPickle' module in Python can be boosted with very little effort. I've also learnt that there's something even faster than a hotted 'cPickle': 'marshal'.]

 Chris McAvoy: Lonely Lion: chiPy meeting for February [John Hunter, the creator of matplotlib, gave a presentation at chiPy on Thursday night. It was extremely impressive. The last time I looked at matplotlib was over a year ago. At the time, I dismissed it as "good for science, not so good for building one-off charts". Now, a year later, the product is still great for science, but I'm happy to report it's also great for guys like me that need to make a bar chart from time to time. A really excellent presentation, and an excellent package.]

 Grig Gheorghiu @ Agile Testing: LA Piggies? Trying to organize Python Interest Group in LA/OC [If anybody is interested in putting together a Python Interest Group in the Los Angeles/Orange County area, please let me know.]

 Slashdot: Python used as modding language for Battlefield 2 [In an interview with Lars Gustavsson of DICE, it was mentioned that Battlefield 2's modding tools are going to be delivered with the game, and that the tools are the same ones used to develop the game. The modding language in use is Python, and will support all aspects of the language.]

 Reg Charney @ Linux Journal: Programming Tools: Refactoring [A look at two open-source refactoring tools, Eclipse for Java and Bicycle Repair Man for Python.]

 Titus Brown @ Advogato: More WSGI [Implemented wsgiMemcached and wsgiPullAdvogato. So you can now have a Web app that pulls down posts from Advogato via XML-RPC and caches the results via memcached.]

 2005-02-11

Bob Ippolito @ from __future__ import *: Mac OS X installer packages for Python stuff [I started consolidating links to the packages I've built with bdist_mpkg. If anyone needs such an installer hosted, just let me know and I'll add it to the list.]

 Max Ischenko @ Max's Blog v0.2.1: Java may not be that bad after all [There is a popular belief (which I'm not going to argue with) that programmer's productivity in Java is "inferior" to that in more dynamic languages, like Python. That's OK but Python seems "inferior" to Java when it comes to innovations (in the environment, not in the language itself). There are many Python packages which started as a port of some Java library. Sometimes these pale imitations evolve into more Pythonic things, sometimes they got replaced by built-from-scratch better alternatives. Of course, there are genuine Python packages which shine even compared with the brightest Java counterparts (Twisted comes to mind first), but these are comparatively few. The ideas' circulation between languages/platforms is a good thing and there is nothing wrong with it. Still, why is the Python-Java relationship so asymmetric? Does it just reflect the difference in size of the respective communities? Is it something else here? Or is my perception skewed?]

 Bob Ippolito @ from __future__ import *: PyObjC 1.3 examplefest [Lots of new PyObjC examples made their way to svn this week.]

 Grig Gheorghiu @ Agile Testing: Python as an agile language [Here are some ideas on why I think Python is an agile language. I use the term agile as in "agile software-development practices", best exemplified by Extreme Programming. I find this definition by Ron Jeffries, from his article "What is Extreme Programming", particularly illuminating: "Extreme Programming is a discipline of software development based on values of simplicity, communication, feedback, and courage. It works by bringing the whole team together in the presence of simple practices, with enough feedback to enable the team to see where they are and to tune the practices to their unique situation." Let's see how Python fares in light of the 4 core XP values: simplicity, communication, feedback and courage.]

 Jim Hughes @ Feet up!: Are you blacklisted? [How do you check if an IP address is blacklisted by one of the various DNS Blackhole Lists? It's sort of easy, you reverse the address (say it was 1.2.3.4), and append the blacklist's address (say blacklist.example.net), and then do a DNS lookup (of 4.3.2.1.blacklist.example.net). If the address is not found, chances are the blacklist hasn't heard of them, otherwise they're probably scum. I've talked about blocking these parasites before, so here's a chunk of code I use in a few places to spot them. It's called blacklist.py and I think it's simple enough to use.]

 Dethe Elza @ Living Code: Slides up [After much delay, my slides are finally up from the VanPyZ talk last week: "Using Python and Cocoa on OS X". Again I'm using Eric Meyer's S5 tool for HTML slides, but it still ends up being a large download because it includes a completely unneccesary quicktime movie. My daughter and I have been playing with iStopMotion and this was one of our first forays into claymation. The reason it's in the slideshow is that movie making is now completely accessible to an eight-year-old, and I want to make writing games and other programs equally accessible to her. Still a ways to go...]

 Fredrik Lundh @ online.effbot.org: The ElementRXP module [Here's a simple module that uses the PyRXP parser to build an element tree. This is a faster than the Python version of ElementTree, but a lot slower than plain ElementTree. However, the PyRXP(U) library supports DTD validation, which can come in handy in some applications.]

 Simon Brunning @ Small Values of Cool: Python meetup at The Stage Door, Waterloo on the 9th [I've had a promising response to my suggested London Python meetup. If even half of them turn up, I'll call it a success.]

 Phillip Pearson @ Second p0st: "It's no coincidence that the star-nosed mole's claws are curved like parentheses" [Brilliant Paul Graham parody: "Taste for the web".]

 James Tauber's Blog: Updated Python Trie implementation [I previously wrote about my BetaCode to Unicode script which used a Trie. A Trie acts like a dictionary but it allows you to match on longest prefix as well as exact matches. I've now pulled out the Trie datastructure and made it available standalone.]

 2005-02-10

Bob Ippolito @ from __future__ import *: Creating standalone Mac OS X applications [py2app 0.1.8 will ship with a new script: macho_standalone. This script takes an application bundle as a parameter, and it does the following: scans every file in the application bundle for Mach-O files; builds a dependency graph of every Mach-O file, copying in Mach-O files that are not part of the bundle already and not in a system location; normalizes the Mach-O headers to point to the right place; strips everything. The functionality in this script is used internally by py2app, with some additional special cases for frameworks. However, this script could be used for applications not written in Python or as part of the build process for other py2app-like environments.]

 Titus Brown @ Advogato: memcached [In an almost completely off-topic response to Graham Fawcett on the quixote-users mailing list, I suggested using memcached to store per-session information in memory. Then I got curious, and wrote a quicky example of using this as a persistence store for Quixote user/session information. Kinda neat - sessions are "persistent" as long as memcached is running.]

 Grig Gheorghiu @ Agile Testing: New Google group: extreme-python [Troy Frever from Aviarc Corporation posted a message to the fitnesse mailing list announcing that he created a Google group for topics related to both Python and Agile methodologies (Extreme Programming and others). Appropriately enough, the name of the group is extreme-python.]

 Philippe Normand @ Base-Art: Inserting legend in images using PIL [I wanted to insert legends directly in images instead of displaying them below respective images. ImageMagick can do that (Text directive) but it's not smart enough to make the legend always visible, even in dark images (if the legend color is black). So I used this script to accomplish that task.]

 Patrick Logan @ Making it Stick: You're getting it our way [I think Bill Venner in his item on static and dynamic is trying too hard to separate language and culture. "Python supports this multi-valued return technique better in its syntax than Java, but I realized that the availability of syntax isn't the main reason I do it in Python. The main reason is that's the way it's usually done in Python. By contrast, that's not the way it's usually done in Java. The cultures are different. The culture that guided the design of a language influences my attitudes and mindset when I use the language. The Java culture encourages me to build solid bricks." I don't think you can untangle the culture and the language, even the syntax. Python the language was influenced by Lisp and Smalltalk. Those languages were developed by, and developed, the dynamic culture that surrounds them.]

 Zach Beane @ xach.com: Taste for the web [Languages which avoid curly braces attract hackers like a W. Somerset Maugham novel attracts thoughtful and sensitive readers, or a Brueghel painting attracts hackers who are also painters. Many people do not know exactly why they find Python so appealing, but it's not hard to guess: the almost complete lack of curly braces speaks to a deep aesthetic instinct that few closely examine. To find hackers with an innate sense of taste, look for those who love Python, even if they can't articulate why.]

 Bill Venners @ Artima Developer: Static versus dynamic attitude [There has been much debate concerning the relative merits of static and dynamic languages. Dynamic language enthusiasts feel they are far more productive when using languages such as Smalltalk, Python, and Ruby compared to static languages like Java or C++. Static language enthusiasts feel that although dynamic languages are great for quickly building a small prototype, languages such as Java and C++ are better suited to the job of building large, robust systems. I recently noticed that in my own day-to-day programming with Java and Python, I approach programming with a different mindset depending upon the language I'm using. I began to wonder to what extent the mindset encouraged by a language and its surrounding culture influences people's perceived productivity when they use that language.]

 Hoang Do @ JotSite.com: foreach from the shell [The code to this and its concept is relatively simple. Yet as I continue to use Unix more and more, the occasional need to do this type of thing from the command-line shell recurs again and again. This script takes a file containing a text list of items and applies a command on each of the items. Thus the notion: foreach.]

 2005-02-09

Python Development Team: Python 2.3.5 [We're happy to announce the release of Python 2.3.5 (final) on Feb 8th, 2005. This is a bug-fix release for Python 2.3. There have been around 50 bugs fixed since 2.3.4 - in the Python interpreter, the standard library and also in the build process. Python 2.3.5 supersedes the previous Python 2.3.4 release. No new features have been added in Python 2.3.5 - the 2.3 series is in bugfix-only mode. 2.3.5 contains an important security fix for SimpleXMLRPCServer. Python 2.3.5 is the last planned release in the Python 2.3 series, and is being released for those people who are stuck on Python 2.3 for some reason. Python 2.4 is a newer release, and should be preferred where possible. From here, bugfix releases will be made from the Python 2.4 branch - 2.4.1 will be the next Python release.]

 Bob Ippolito @ from __future__ import *: PIL 1.1.5b3 for Mac OS X 10.3 [I've put together a quick bdist_mpkg build of PIL 1.1.5b3.]

 Fredrik Lundh @ online.effbot.org: Python Imaging Library 1.1.5 beta 3 [PIL 1.1.5b3 is 1.1.5b2 plus a WBMP codec, and a couple of bug fixes.]

 Phillip Eby @ Dirt Simple: From nine to five... [...microseconds, that is. Just when I thought I couldn't possibly pull any more optimization tricks in pure Python, I found a couple more tonight. First, it occurred to me that because generic function wrappers use a dispatcher instance's __getitem__, I could pre-compute the __getitem__ access. The next thing I did was a data structure change, in order to drop another pair of attribute accesses.]

 Ben Last @ The Law of Unintended Consequences: Zope, threads and things that are not modules [You may, like me, have been misled by part of the Zope management interface into believing something that's not true; that External Methods live in a module. But they're not modules; at least, not in key senses of the word. What actually happens is that the source file is loaded, compiled and the resulting code object is then used to get access to the methods as needed. This is clever, but has some unfortunate side-effects, one of which is that it isn't possible to rely on certain module-level semantics.]

 Titus Brown @ Advogato: Daily Python-URL no longer feeding to Planet Python? [This no longer shows up on Planet Python, it seems. Intentional?]

 James Tauber's Blog: BetaCode to Unicode in Python [BetaCode is a common ASCII transcription for Polytonic Greek. I've been dealing with it for around twelve years. For the last six years, my preference has been to use Unicode, so I wrote a program (initially in Java but then in Python) that used a Trie to represent the multiple BetaCode characters that can map to a single pre-composed Unicode character. I've had a version available on this site since 2002, but I've now updated it to what I've been using for my most recent work.]

 Guyon Moree @ Gumuz' Devlog: Python Half Life / Counter-strike server info query [A while back I wrote a Python script to query a Half Life / Counter-Strike server for its game details. It was useful enough for me so it might be useful enough for you. It does not work with the new protocol the new source engine uses. I might update it in the future to support that too.]

 scrottie @ use Perl;: Perl6 vs Java vs Python, or, Java sucks! and the NIH fallacy [I recently wrote a book that deals with doing things (using features and ideas) in Perl invented and popularized by other languages. People react to that statement in fascinating ways - "What? Why would you want to do something in Perl that another language does? Perl is better so using things from Java and Python is pointless," they say. I'm going to show first why the quoted statement is dumb and then secondarily why it's a fallacy.]

 2005-02-08

Guyon Moree @ Gumuz' Devlog: Wanted: Open-source Python project [I think it's a good idea to join an open-source project to enhance my skills. At the same time I can contribute to open source, which I like to do because I enjoy using open-source software every day. I scoured SourceForge yesterday, looking for a fun Python project. Preferably I'd like to do something Internet-related. I found 2 great looking Bittorrent clients: G3Torrent and PTC. I wrote both developers that I'd like to participate in the development of their applications. Didn't get any answers yet though, but we'll see. Anyway, these apps are not the only thing I'm interested in, so if anyone needs a Python developer for their open-source project: contact me!]

 Ryan @ :-$: Code generation with Python, Cog, and Nant [We've been using C# for a couple of years now, and are getting tired of the verbosity. Especially tired of copy/pasting and changing a couple of identifiers, and I imagine many other people are, too. After seeing some of the macro capabilities of Lisp, we got jealous. After some googling and browsing, I ran across Ned Batchelder's Python-based code generation tool, Cog. Cog lets you build ad-hoc code generators in Python, in your source code files. I use Cog in concert with Nant and Visual Studio for working on C# projects.]

 Paul Everitt @ Zope Dispatches: Zope at LOTS [LOTS ("Let's Open The Source") had their first conference last year and quite a few people had good things to say about it. LOTS is being held Feb 17-19 this year in Berne, Switzerland. There will be several Zope-related activities: 1) Infrae, a Zope Europe managing partner, is giving talks on Silva and Kupu. 2) Bernhard Buehlmann from 4teamwork is giving a talk on Plone. 3) There is also a Zope 3 talk planned.]

 Bob Ippolito @ from __future__ import *: IDN spoofing defense for Safari [Soon after I got home from ShmooCon, I saw that the Shmoo Group came up with a new domain spoofing exploit for which "no defense exists". It's pretty amazing that browsers actually implement IDN without any kind of protection, so I decided to quickly hack up a defense for Safari on Mac OS X 10.3 (and probably later).]

 Richard Jones' Stuff: pyblagg re-blat solved [I finally figured out why pyblagg was being blatted (all of a given feed's entries show up in a block in the page, even ancient ones) by some people's weblogs even at this stage. I've been running it in test mode for a little over a week, and figured that all the undated feeds had all been fetched by now. The catch (until this morning) was that when I fetched an undated feed, I assigned the fetch date to the feed's new entries in the feed so they'd be reasonably sorted with the other entries which are dated. Unfortunately, I also cleaned out the entries database, removing entries older than a week. Therefore those undated feeds would get re-fetched, and thus re-blat the pyblagg page, once a week. Sigh. Undated feeds. So now I only delete the old feed entries if there's > 200 (arbitrary number) entries for the feed. Hopefully that'll reduce or elminate the blatting from undated feeds.]

 Titus Brown @ Advogato: MythTV, Freevo, and Python [Mentions of free/OSS PVRs showed up in two high-profile places recently. First there was a NY Times article on MythTV, and then a LinuxDevCenter article on Freevo showed up on Slashdot. The Freevo team had some nice things to say about Python: "The language is one of the best collaborative languages I have ever used. I wonder if we could have reached the point we have without the short learning curve and power of Python and its related libraries." But also talked a bit about speed considerations: "Sometimes Python is too slow for the needed task. Most of the time we can avoid such problems by rethinking the design." I'd be interested in hearing more about this, because my usual solution is to recode in C ;).]

 Richard Jones' Stuff: Still working out the kinks in pyblagg [From the recent updates, it looks like there's still a few undated blogs it hadn't managed to fetch from until now. Sigh. Undated entries mess up an aggregator like this when their blog is first fetched. It'll calm down though.]

 Titus Brown @ Advogato: WSGI and IIS [Mark Rees posts about an IIS server interface for WSGI. I can't test this, because I don't run IIS at all, but it would be interesting to see if my simple Quixote-WSGI adapter works under it. I don't even know how people run Quixote on Windows, to be honest; I guess SCGI should work with Apache on Windows, right? So, I asked Mark to try it out, and it turns out that the QWIP adapter successfully runs Quixote under a slightly modified version of his ISAPI. Very cool. I hadn't thought about IIS integration being a real raison d'etre for WSGI, but there it is...]

 Mark Rees @ Web-SIG: Ann: ISAPI-WSGI 0.4 beta [I am happy to announce the release of ISAPI-WSGI 0.4 beta. ISAPI-WSGI will (hopefully) allow any WSGI application to run inside a Windows webserver that supports ISAPI. It has one major limitation being that it is only single threaded. I am currently working on a fully threaded version, but wanted to release it now so others could have a look at it.]

 Titus Brown @ Advogato: Python urllib2 buggishness [John Lee ran across my old post asking about new (RFC 2965) style cookies, and answered thusly: Mailman correctly uses RFC 2965 cookies, but does so unnecessarily because no one is really paying any attention to them. However, he did say that it's a bug for urllib2 to not correctly handle things that the browsers handle. The fix is to change urllib2 to handle RFC 2965 cookies by default, I guess. I sent him a concise program that demonstrated the issue.]

 2005-02-07

Phillip Eby @ Dirt Simple: Optimization surprises [This weekend, I took another crack at trimming microseconds off the common-case path for generic-function execution, and succeeded in dropping the execution time from 13.2 microseconds to just over 9.8. (Which is about 9 microseconds overhead added relative to a hand-optimized Python version of the same trivial function.) Along the way, however, I realized a couple of surprising things about Python performance tuning.]

 Jeffrey Shell @ Industrie Toulouse: More test-driven development [There's a pretty good article at ONLamp.com about "More test-driven development in Python". It's basic unit-test work, showcasing writing tests first and code second. I'm never consistent when it comes to unit testing. On some projects, I use them. On others, I don't. Sometimes, it's just really hard to unittest code meant for Zope 2. Zope 3, on the other hand, advocates testing heavily. In the developer documentation for writing a new content object, writing unit tests is step #4, just after preparation, initial design, and writing interfaces. It's great to see testing promoted so heavily in its documentation. On a recent project, I was reminded (again) of how nice it is to have unit tests.]

 Max Ischenko @ Max's Blog v0.2.1: Efficient XML processing in Python [In a recent article "Decomposition, process, recomposition" on XML.com, Uche Ogbuji talks about strategies for processing large XML documents in Python. This led me to recall my own experience with processing of multimegabytes XML documents. In general, there are at least three popular strategies to deal with XML in Python: SAX-based, DOM-based and, er, shall I say, Pythonic. SAX realizes a speedy and memory-light approach but which shifts the burden of keeping a processing context onto programmer. DOM gives cross-language, standard, verbose and memory-hungry strategy which scale poorly for large documents. By Pythonic I meant a range of libraries, like gnosis.xml.objectify, ElementTree, xmltramp which share a common attitude to provide a nice, Python-friendly API. I used ElementTree (and recently, it's new, C-based reincarnation) library. The task was as follows: given a large (~10Mb) XML document of some complex structure the program had to significantly 'extend' it with new information and write to a new file.]

 Ian Bicking: Of syntax and environment [I was reading Robert Lefkowitz's post "What makes a programming language great?". In it he (kind of) argues against overly-simple core languages. I can see Robert's point. But in doing so I think he argues two kinds of simplicity - both of which are arguable, but are also very different. There's syntactic simplicity, and the simplicity of homogeneity. I.e., pure syntax vs a pure runtime. With syntactic simplicity you get languages like Lisp, Smalltalk, Logo, and Tcl (in more-or-less increasing order of simpicity and regularity). On the other hand, you can have a pure runtime. Examples include Java (excluding JNI), Squeak, and many Schemes. Python is a particularly impure runtime. It's made significant compromises in the process. The GIL is largely there to make it easier to mix Python and C code. As a syntax, Python is middling. It resists (non-traditional) punctuation. It tries to leverage its syntax for multiple uses. It has uniformity of attribute access. But it's largely programmatically opaque, and there's never been any effort to keep it down to a core of primitive constructs.]

 Kevin Dangoor @ Blue Sky on Mars: End-to end Unicode in Python ["End-to-end Unicode web applications in Python" by Martin Doudoroff probably would have been very helpful to me a few days ago, but I only just now stumbled across it. I've already achieved Unicode enlightenment, but if you find yourself struggling with it, this doc looks like a good place to start!]

 Ian Bicking: A theory on form toolkits [I was talking about FormEncode on the Subway list recently, and about where form generation fits in, and how it might all work, and I came to some realizations. FormEncode has been challenging for me, and a big part was because I didn't know what belonged to who. Is validation part of your domain objects? Is form generation and modeling part of your validation? Is validation part of your form model? When you start creating complex and compound forms (and validation schemas) what belongs to what? It was a mess. I thought adaptation would help, but I think it only maked things more complex. But I'm happy with the validation, and I'm happy with htmlfill. Peter Hunt really wants form generation too - understandable, because it's a really important part of Rails and prototyping objects. I did that in FormEncode, but the result felt fragile, especially in the face of tweaking (an inevitability for forms). This is the design I now like: validation + form generation -> htmlfill -> fully rendered form]

 Swaroop C H @ The Dreamer: Redesigned my book website [I have redesigned ByteOfPython.info and now have a nice and clean theme based on Negen. The content on the website has been reorganized and updated as well.]

 Simon Brunning @ Small Values of Cool: Batch iTunes track conversion with Python and COM [I think that I see now why Apple has kept the iTunes interface simple. It's a simple app that does simple stuff, and it just works. Anyway, on to my latest folly, batch conversion. I initially ripped all my CDs to MP3 at 192 kbps. I've since discovered that I'm quite happy with plain old 128 kbps. So I want to convert a bunch of track to a lower bitrate. Now, you can convert from one format and bit rate to another in iTunes, but the process leaves a lot to be desired. So, I came up with a script to do the conversion for me.]

 Fredrik Lundh @ online.effbot.org: ElementTidy 1.0 beta 1 [ElementTidy 1.0 beta 1 is 1.0a3 plus improved support for source document encoding, and more aggressive tidying (earlier versions gave up earlier on seriously malformed HTML). Enjoy!]

 Kevin Dangoor @ Blue Sky on Mars: Deferring SQLObject database insertion until later [SQLObject automatically inserts entries in the database as soon as you instantiate them. It also runs updates as soon as you modify attributes. This is not always desirable and Peter Butler has a recipe to choose when to insert and update new objects. It's actually not a bad solution, because the object you're holding is not truly one of your domain objects. That makes it clearer that something more needs to be done with it. SQLObject could implement this functionality directly with a flag passed to __init__. There is already a flag to defer the updates: just set _lazyUpdate = True on the object, then you need to run syncUpdate or sync to save the data.]

 Grig Gheorghiu @ Agile Testing: Web app testing with Jython and HttpUnit [There's been a lot of talk recently about "dynamic Java", by which people generally mean driving the JVM by means of a scripting language. One of the languages leading the pack in this area is Jython (the other one is Groovy). Jython is steadily making inroads into the world of test frameworks. I want to show here how to use Jython for interactively driving a Java test tool (HttpUnit) in order to verify the functionality of a Web application.]

 Swaroop C H @ The Dreamer: Bangalore Pythonistas list is high traffic! [The BangPypers group was created just 10 days ago. There has already been 202 messages by 44 members! Just goes to show that Python is alive and kicking in Bangalore.]

 Ian Bicking: WSGIKit/WSGI/Webware Sprint [I've listed a sprint for PyCon on WSGIKit, WSGI, and Webware. It's really along the lines of the WSGI library that I mentioned earlier - particularly oriented towards making WSGIKit a good replacement for Webware by adding all the missing pieces, doing so as WSGI Middleware. So I think it really has applicability to anyone interested in Python web programming and WSGI. If you are interested, please add yourself to the WSGIKit Sprint page on the Python Wiki.]

 Richard Jones' Stuff: New pyblagg generator running [The new, improved pyblagg is up and running. The new parser handles many more feeds; has better handling of broken feeds (data does leak, script doesn't die, feed is marked broken); uses the latest feedparser which supports many more feed formats, and automatically handles if-modified-since; has an automated scraper to handle new / modified / deleted feeds listed on the wiki (runs once a week and tells me what it's done so that people spamming the wiki page will be ineffective); uses an sqlite database to store state; and is just generally much neater code that my old massively-hacked script :) Feeds that don't support if-modified-since are listed under "no-update*" since they're fetched but we got no new entries.]

 Simon Brunning @ Small Values of Cool: A quick look at ElementTree [Lumigent Log Explorer is a potential life saver, but we've had trouble getting exactly what we want from it in exactly the way that we want it. No matter - it has a facility to export all your raw transactions to XML. Sorted! I've not processed much XML, and not done any at all for a while. I was never really comfortable with the Python XML libraries that I'd played with, so I thought I'd give the effbot's ElementTree module a try. The API is lovely. After no more than five or ten minutes, I felt like I knew what I was doing.]

 2005-02-06

Tim Lesher @ Aftermarket Pipes: Argh: too much fun, too little time [I've accumulated way too many neat ideas I want to try out (mostly Python-related). In no particular order: thinking about what it would take to make a true, single-executable py2exe on Windows; exploring Karrigell and CherryPy, benchmarking them, and contrasting them with Rails; thinking about how to make PyPI work more like CPAN or Gems; checking out the update of wxPython on FreeBSD; checking out the new version of WingIDE and comparing/contrasting it with SPE; thinking about the equivalent of WTL for Python: take the bare-metal approach of venster, then apply the clean, Pythonic interface of Wax ...and I know there are one or two I've forgotten. I think there are some sprints going on at Pycon for some of the above, but I can't make it due to work obligations. On the other hand, I seem to have acquired a free weekend sans wife and kids, so maybe I'll be able to pick one of the above and hack for a few hours.]

 comments?  ::: rss 2.0 (compact) ::: rss 2.0 (full; experimental) ::: powered by blogger™ 


  </body>
</html>
