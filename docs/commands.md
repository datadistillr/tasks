# CLI Commands

Included in the package are several commands that can be run from that CLI that provide that bit of emergency
help you might need when something is going wrong with a cron job at 1am on a Saturday. 

All commands are run through CodeIgniter's `spark` cli tool: 

    > php spark tasks:list
    > php spark tasks:run

## Available Commands

**tasks:list**

    > php spark tasks:list

This will list all available tasks that have been defined in the project, along with their type and
the next time they are scheduled to run.

    +---------------+--------------+-------------+----------+---------------------+-------------+
    | Name          | Type         | Schedule    | Last Run | Next Run            | Runs        |
    +---------------+--------------+-------------+----------+---------------------+-------------+
    | emails        | command      | 0 0 * * *   | --       | 2020-03-21-18:30:00 | in 1 hour   |
    +---------------+--------------+-------------+----------+---------------------+-------------+

**tasks:disable**

    > php spark tasks:disable 

Will disable the task runner manually until you enable it again. Stores the setting in the default
database through the [Settings](https://github.com/codeigniter4/settings) library.

**tasks:enable**

    > php spark tasks:enable

Will enable the task runner if it was previously disabled, allowing all tasks to resume running. 

**tasks:run**

    > php spark tasks:run
    
This is the primary entry point to the Tasks system. It should be called by a cron task on the server
every minute in order to be able to effectively run all the scheduled tasks. You typically will not
run this manually.

You can run the command and pass the `--task` option to immediately run a single task. This requires
the name of the task. You can either name a task using the `->named('foo')` method when defining the 
schedule, or one will be automatically generated. The name can be found using `tasks:list`.

    > php spark tasks:run --task emails
