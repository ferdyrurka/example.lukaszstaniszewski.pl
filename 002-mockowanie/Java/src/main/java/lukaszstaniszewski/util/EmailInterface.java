package lukaszstaniszewski.util;

/**
 * The interface Email interface.
 */
public interface EmailInterface {
    /**
     * Send boolean.
     *
     * @param email the email
     * @return the boolean
     * @throws Exception the exception
     */
    public boolean send(String email) throws Exception;
}
