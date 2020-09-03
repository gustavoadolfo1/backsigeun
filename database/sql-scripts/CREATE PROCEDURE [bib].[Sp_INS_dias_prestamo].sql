-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_INS_dias_prestamo]
	  @_cDescriDiasPrestamos VARCHAR(15),
      @_cTipo_Persona VARCHAR(15),
      @_iNumeroDias INTEGER,
      @_bHabilitado BIT,
	   /*Campos de auditoria*/
      @_cUsuarioSis VARCHAR(50),
      @_cEquipoSis VARCHAR(50),
      @_cIpSis VARCHAR(15),
      @_cMacNicSis VARCHAR(35)
AS
BEGIN
	SET NOCOUNT ON;
    DECLARE @cUsuarioSis VARCHAR(50)
    SELECT @cUsuarioSis=c.cCredUsuario
    FROM seg.credenciales AS c
    WHERE c.iCredId=@_cUsuarioSis
    IF @@ERROR<>0 GOTO ErrorCapturado

    DECLARE @cMensaje VARCHAR(MAX)

    IF @_cDescriDiasPrestamos IS NULL
		BEGIN
        SET @cMensaje='Falta especificar la descripción dias prestamo!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
	IF @_cTipo_Persona IS NULL
		BEGIN
        SET @cMensaje='Falta especificar el tipo de persona!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
	IF @_iNumeroDias IS NULL
		BEGIN
        SET @cMensaje='Falta especificar el numero de días!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END


    INSERT INTO bib.dias_prestamo
        (
         cDescriDiasPrestamos,
         cTipo_Persona,
         iNumeroDias,
         bHabilitado,
         cUsuarioSis,
         dFechaSis,
         cEquipoSis,
         cIpSis,
         cOpenUsr,
         cMacNicSis
        )
    VALUES
        (
            @_cDescriDiasPrestamos,
            @_cTipo_Persona,
            @_iNumeroDias,
            @_bHabilitado,
            @_cUsuarioSis,
            GETDATE(),
            @_cEquipoSis,
            @_cIpSis,
            'N',
            @_cMacNicSis
		)

    COMMIT TRANSACTION
    SELECT 1 AS iResult
    RETURN 1
    ErrorCapturado:
    ROLLBACK TRANSACTION
    SELECT 0 AS iResult
    RETURN 0
END
GO
