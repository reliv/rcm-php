# RCM Site Settings Sections

Allows creation of simple per-site settings that are structured and organized into named sections and stored in a shared database table.

Creation of a settings section is done entirely from within module configuration for any consuming modules that want to add a section of admin-configurable settings for themselves. After the settings tables are set up properly in the database, no database changes are needed for adding new sections.

Settings are loaded and saved one whole section at a time. Saving a section overwrites the entire section, so be careful not to accidentally delete any sub-keys within it.

## TODO: Describe the API for creating new sections in a module
