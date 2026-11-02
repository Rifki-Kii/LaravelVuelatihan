/**
 * JSDoc TypeDef untuk entitas API
 */

/**
 * @typedef {Object} User
 * @property {number} id
 * @property {string} name
 * @property {string} email
 */

/**
 * @typedef {Object} Project
 * @property {number} id
 * @property {number} user_id
 * @property {string} name
 * @property {string|null} description
 * @property {string} status
 * @property {string} created_at
 * @property {string} updated_at
 */

/**
 * @typedef {Object} Task
 * @property {number} id
 * @property {number} project_id
 * @property {number} user_id
 * @property {string} title
 * @property {string|null} description
 * @property {string} status
 * @property {string} priority
 * @property {string|null} due_date
 * @property {string} created_at
 * @property {string} updated_at
 */

/**
 * @typedef {Object} Comment
 * @property {number} id
 * @property {number} task_id
 * @property {number} user_id
 * @property {string} body
 * @property {string} created_at
 * @property {string} updated_at
 */

/**
 * @typedef {Object} Tag
 * @property {number} id
 * @property {string} name
 * @property {string} color
 * @property {string} created_at
 * @property {string} updated_at
 */

/**
 * @typedef {Object} ApiResponse
 * @property {Object|null} data
 * @property {string} message
 */
